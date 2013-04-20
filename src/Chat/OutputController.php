<?php
namespace Chat;

class OutputController extends \Savvy
{
    public function __construct($options = array())
    {
        parent::__construct();
        $this->initialize($options);
    }

    public function initialize($options = array())
    {

        switch ($options['format']) {
            case 'html':
                // Always escape output, use $context->getRaw('var'); to get the raw data.
                $this->setEscape(function($data) {
                    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8', false);
                });
                header('Content-type:text/html;charset=UTF-8');
                $this->setTemplateFormatPaths('html');
                break;
            default:
                throw new Exception('Invalid/unsupported output format', 500);
        }
    }

    /**
     * Set the array of template paths necessary for this format
     *
     * @param string $format Format to use
     */
    public function setTemplateFormatPaths($format)
    {
        $web_dir = dirname(dirname(__DIR__)) . '/www';

        $this->setTemplatePath(
            array(
                $web_dir . '/templates/' . $format,
            )
        );
    }
}