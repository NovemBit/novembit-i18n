<?php


namespace NovemBit\wp\plugins\i18n\system;


use NovemBit\wp\plugins\i18n\Bootstrap;

class Option
{

    private $_name;
    private $_default;

    const TYPE_BOOL = 'bool';
    const TYPE_TEXT = 'text';

    const MARKUP_CHECKBOX = 'checkbox';
    const MARKUP_TEXT = 'text';
    const MARKUP_NUMBER = 'number';
    const MARKUP_SELECT = 'select';

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
        $markup = $this->getParam('markup', null);

        $value = $this->getValue();
        $html = '';

        switch ($type) {
            case self::TYPE_BOOL:
                $html .= sprintf('<input class="%s" id="%s" type="checkbox" name="%s" %s/>',
                    implode(' ', [$type, $method]),
                    $this->getName(),
                    Bootstrap::getOptionName($this->getName()),
                    $this->getValue() ? 'checked' : ''
                );
                break;
            default:
                if (!empty($values)) {
                    if ($markup == null || $markup == self::MARKUP_SELECT) {
                        $html .= sprintf('<select class="%s" id="%s" name="%s" %s>',
                            implode(' ', [$type, $method]),
                            $this->getName(),
                            Bootstrap::getOptionName($this->getName()) . ($method == self::METHOD_MULTIPLE ? '[]' : ''),
                            $method == self::METHOD_MULTIPLE ? 'multiple="multiple"' : ''
                        );
                    }

                    foreach ($values as $key => $_value) {
                        if ($markup == null || $markup == self::MARKUP_SELECT) {

                            $html .= sprintf('<option value="%s" %s>%s</option>',
                                $key,
                                (($key == $value) || (is_array($value) && in_array($key, $value))) ? 'selected' : '',
                                $_value
                            );
                        } elseif ($markup == self::MARKUP_CHECKBOX) {
                            if ($method == self::METHOD_MULTIPLE) {
                                $html .= sprintf('<div><label><input type="checkbox" name="%s" value="%s" %s>%s</label></div>',
                                    Bootstrap::getOptionName($this->getName()) . ($method == self::METHOD_MULTIPLE ? '[]' : ''),
                                    $key,
                                    (($key == $value) || (is_array($value) && in_array($key, $value))) ? 'checked' : '',
                                    $_value
                                );
                            } else{
                                $html .= sprintf('<div><label><input type="radio" name="%s" value="%s" %s>%s</label></div>',
                                    Bootstrap::getOptionName($this->getName()),
                                    $key,
                                    (($key == $value) || (is_array($value) && in_array($key, $value))) ? 'checked' : '',
                                    $_value
                                );
                            }
                        }
                    }

                    if ($markup == null || $markup = self::MARKUP_SELECT) {
                        $html .= '</select>';
                    }

                } elseif ($method == self::METHOD_MULTIPLE) {
                    foreach ($this->getValue() as $key => $_value) {
                        if (!empty($_value)) {
                            $html .= sprintf('<div class="group"><input name="%s" type="text" value="%s">%s</div>',
                                Bootstrap::getOptionName($this->getName()) . '[]',
                                $_value,
                                '<button class="button button-secondary" onclick="this.parentElement.remove()">X</button>'
                            );
                        }
                    }
                    $html .= sprintf('<div class="group"><input name="%s" type="text"></div>',
                        Bootstrap::getOptionName($this->getName()) . '[]'
                    );
                    $html .= sprintf('<div class="group"><button class="button button-primary" type="button" onclick="%s">Add new</button></div>',
                        "var c = this.parentElement.previousSibling.cloneNode(true); c.children[0].value=''; this.parentElement.parentElement.insertBefore(c,this.parentElement);");
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

        return $html;
    }
}