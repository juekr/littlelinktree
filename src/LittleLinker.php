<?php 
namespace Juekr\LittleLinker;

use Exception;
use Symfony\Component\Yaml\Yaml;

class LittleLinker 
{
    private $yaml, $config, $all_buttons, $buttons;
    private $url, $domain;

    function __construct($config_file)
    {
        $this->yaml = (object)Yaml::parse(file_get_contents($config_file));
        $this->config = (object)$this->yaml->config;
        $this->all_buttons = (object)$this->yaml->button_shortcuts;
        $this->buttons = (array)$this->config->buttons;
    }

    public function is_edit_mode(): bool
    {
        try 
        {
            return intval($_GET["edit"] ?? 0) == 1;
        } 
        catch(Exception $e) 
        {
            return false;
        }
    }

    /* -- automatically build url/domain or load from config -- */
    private function extract_domain(): array 
    {
        $this->url = $this->config->url ?? strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, strpos($_SERVER["SERVER_PROTOCOL"], "/"))). "://". $_SERVER["HTTP_HOST"] .$_SERVER["REQUEST_URI"];
        $this->domain = preg_replace('!^.*://(.*?)[:/].*$!isu', '$1', $this->url);
        return array($this->url, $this->domain);
    }

    public function get_url(): string
    {
        if (empty($this->url)) return $this->extract_domain()[0];
        return $this->url;
    }

    public function get_domain(): string
    {
        if (empty($this->domain)) return $this->extract_domain()[1];
        return $this->domain;
    }
    /* -- end domain/url from config -- */

    function get_icon_by_keyword($str, $default = null) 
    {
        foreach ($this->all_buttons as $btn):
            if ($btn["name"] == $str) return $btn["icon"];
        endforeach;
        return $default;
    }

    public function get_buttons() 
    {
        return $this->config->buttons ?? [];
    }

    public function get_button_class($button) 
    {
        $class = $button->class ?? "button-".strtolower($button->name ?? "untitled");
        foreach($this->all_buttons as $compare):
            if ($compare["class"] == $class) return $class;
        endforeach;
        return "button-default";
    }

    /* -- getters/setters and defaults -- */

    public function get_title(string $which = null, $default = null) 
    {
        $titles = array(
            "h1" => $this->config->h1 ?? "",
            "meta" => $this->config->meta_title ?? "",
            "default" => $this->get_domain()
        );
        // if (empty($default)) $default = $titles["default"];
        // $return = false;
        // foreach($titles as $key => $value):
        //     if (empty($which) || $key == $which || $key == "default") $return = true;
        //     if ($return && !empty($value)) return $value;
        // endforeach;
        // return $default;
        return $this->abstract_getter($titles, $which, $default);
    }

    public function get_description(string $which = null, $default = null) 
    {
        $texts = array(
            "meta" => $config->meta_description ?? "",
            "tagline" => $config->tagline ?? "",
            "default" => $this->get_title("meta")
        );
        return $this->abstract_getter($texts, $which, $default);
    }

    public function get_author() 
    {
        return $this->config->meta_author ?? $this->get_title();
    }

    public function get_tags(bool $as_string = false) 
    {
        $tags = $config->meta_tags ?? [];
        if (gettype($tags) == "array" && $as_string === false) return $tags;
        if (gettype($tags) == "array" && $as_string === true) return implode(", ", $tags);
        if (gettype($tags) == "string" && $as_string === false) return array_map("trim", explode(",", $tags));
        if (gettype($tags) == "string" && $as_string === true) return $tags;
    }

    public function get_icon(string $which = null, string $default = null)
    {
        $icons = array(
            "avatar" => $this->config->avatar ?? "",
            "favicon" => $this->config->meta_favicon ?? "",
            "favicon-2x" => str_replace(".", "@2x.", $this->config->meta_favicon ?? ""),
            "default" => "images/avatar.png"
        );
        return $this->abstract_getter($icons, $which, $default);
    }

    private function abstract_getter(array $array = [], string $key = null, string $default = null) 
    {
        if (empty($default)) $default = $array["default"];
        if (!empty($key)):
            $elem = $array[$key];
            unset($array[$key]);
            $array = array($key => $elem) + $array;
        endif;
        $return = false;
        foreach($array as $key => $value):
            if (empty($which) || $key == $which || $key == "default") $return = true;
            if ($return && !empty($value)) return $value;
        endforeach;
        return $default;
    }
}
?>