<?php

class Page {
    private $template;

    public function __construct($template) {
        if (!file_exists($template)) {
            throw new Exception("Template not found: $template");
        }
        $this->template = file_get_contents($template);
    }

    public function Render($data) {
        $output = $this->template;

        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $output = str_replace($placeholder, htmlspecialchars($value), $output);
        }

        return $output;
    }
}
