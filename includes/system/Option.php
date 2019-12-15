<?php


namespace NovemBit\wp\plugins\i18n\system;


use NovemBit\wp\plugins\i18n\Bootstrap;

class Option
{

    private $_name;
    private $_default;

    const TYPE_BOOL = 'bool';
    const TYPE_TEXT = 'text';

    const METHOD_SINGLE = 'single';
    const METHOD_MULTIPLE = 'multiple';

    private $_params = [];


    public function __construct($_name, $_default = null, $_params = [])
    {
        $this->setName($_name);
        $this->setDefault($_default);
        $this->setParams($_params);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->_default;
    }

    /**
     * @param mixed $default
     */
    public function setDefault($default): void
    {
        $this->_default = $default;
    }

    public function getValue()
    {
        return Bootstrap::getOption($this->getName(), $this->getDefault());
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->_params;
    }

    public function getParam($param, $default = null)
    {
        return $this->_params[$param] ?? $default;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->_params = $params;
    }

    public function getField()
    {
        $type = $this->getParam('type', null);
        $method = $this->getParam('method', self::METHOD_SINGLE);
        $values = $this->getParam('values', []);

        $value =$this->getValue();
        $html = '';

        switch ($type) {
            case self::TYPE_BOOL:
                $html .= sprintf('<input id="%s" type="checkbox" name="%s" %s/>',
                    $this->getName(),
                    Bootstrap::getOptionName($this->getName()),
                    $this->getValue() ? 'checked' : ''
                );
                break;
            default:
                if (!empty($values)) {
                    $html .= sprintf('<select id="%s" name="%s" %s>',
                        $this->getName(),
                        Bootstrap::getOptionName($this->getName()) . ($method == self::METHOD_MULTIPLE ? '[]' : ''),
                        $method == self::METHOD_MULTIPLE ? 'multiple="multiple"' : ''
                    );
                    foreach ($values as $key => $_value) {
                        $html .= sprintf('<option value="%s" %s>%s</option>',
                            $key,
                            ($_value == $value || (is_array($value) && in_array($key,$value))) ? 'selected' : '',
                            $_value
                        );
                    }
                    $html .= '</select>';
                } elseif ($method != self::METHOD_MULTIPLE) {
                    $html .= sprintf('<input id="%s" type="text" name="%s" value="%s"/>',
                        $this->getName(),
                        Bootstrap::getOptionName($this->getName()),
                        $this->getValue()
                    );
                } else {
                    $html .= "Not handled";
                }
                break;
        }

        $html='<div data-value="'.esc_html(json_encode($this->getValue())).'">'.$html.'</div>';
        return $html;
    }
}