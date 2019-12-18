<?php


namespace NovemBit\wp\plugins\i18n\system;


use NovemBit\wp\plugins\i18n\Bootstrap;

class Option
{

    private $_name;
    private $_default;

    const TYPE_BOOL = 'bool';
    const TYPE_TEXT = 'text';
    const TYPE_OBJECT = 'object';
    const TYPE_GROUP = 'group';

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
        $parent = $this->getParam('parent', null);
        $type = $this->getParam('type', null);
        $method = $this->getParam('method', self::METHOD_SINGLE);
        $values = $this->getParam('values', []);
        $markup = $this->getParam('markup', null);
        $template = $this->getParam('template', null);
        $field = $this->getParam('field', null);
        $disabled = $this->getParam('disabled', false);
        $readonly = $this->getParam('readonly', false);

        $data = $this->getParam('data', []);


        $value = $this->getValue();
        $name = $this->getName();
        $html = self::_getField([
            'type' => $type,
            'parent' => $parent,
            'method' => $method,
            'values' => $values,
            'markup' => $markup,
            'template' => $template,
            'field' => $field,
            'value' => $value,
            'name' => $name,
            'data' => $data,
            'disabled' => $disabled,
            'readonly' => $readonly
        ]);
        return $html;
    }

    private static function _encodeKey($key)
    {
        return "{{encode_key}}" . base64_encode($key);
    }

    private static function _maybeDecodeKey($str)
    {
        if (strpos($str, '{{encode_key}}') === 0) {
            $str = preg_replace('/^{{encode_key}}/', '', $str);
            return base64_decode($str);
        }
        return $str;
    }

    private static function _maybeBoolean($str)
    {
        if($str == '{{boolean_true}}'){
            return true;
        } elseif($str == '{{boolean_false}}'){
            return false;
        }
        return $str;
    }

    private static function _getField($params = [])
    {
        $parent = $params['parent'] ?? null;
        $type = $params['type'] ?? null;
        $method = $params['method'] ?? null;
        $values = $params['values'] ?? [];
        $markup = $params['markup'] ?? null;
        $template = $params['template'] ?? null;
        $field = $params['field'] ?? null;

        $value = $params['value'] ?? null;
        $name = $params['name'] ?? null;

        if ($parent != null) {
            $name = $parent . '[' . $name . ']';
        }

        $disabled = $params['disabled'] ?? false;
        $disabled_str = $disabled ? "disabled" : "";
        $readonly = $params['readonly'] ?? false;
        $readonly_str = $readonly ? "readonly" : "";

        $data = $params['data'] ?? [];
        $data['name'] = $data['name'] ?? $name;
        $data_str = implode(' ', array_map(
            function ($k, $v) {
                if (is_string($v)) {
                    $v = htmlspecialchars($v);
                }
                return 'data-' . $k . '="' . $v . '"';
            },
            array_keys($data), $data
        ));

        $html = '';

        switch ($type) {
            case self::TYPE_BOOL:
                $html .= sprintf('<input value="{{boolean_false}}" type="hidden" name="%1$s"/>',
                    $name
                );
                $html .= sprintf('<input class="%1$s" id="%2$s" value="{{boolean_true}}" type="checkbox" name="%2$s" %3$s %4$s %5$s %6$s/>',
                    implode(' ', [$type, $method]),
                    $name,
                    $value ? 'checked' : '',
                    $readonly_str,
                    $disabled_str,
                    $data_str
                );
                break;
            case self::TYPE_OBJECT:
                $on_change = "var fields = this.parentElement.querySelectorAll('[name]'); for(var i=0; i<fields.length; i++){ var field= fields[i]; if(this.value!=null){field.removeAttribute('disabled')}; if(field.getAttribute('data-name') == null){ field.setAttribute('data-name',field.getAttribute('name')) } var attr = field.getAttribute('data-name'); attr = attr.replace('{key}',btoa(this.value)); fields[i].setAttribute('name',attr); }";

                if ($template != null && !empty($template)) {
                    foreach ($value as $key => $_value) {
                        $html .= "<div>";
                        $html .= sprintf('<input class="key" type="text" placeholder="key" value="%s" onchange="%s" >',
                            htmlspecialchars($key),
                            $on_change);
                        $html .= "<div>";

                        foreach ($template as $_key => $_field) {

                            $_field['value'] = $_value[$_key] ?? null;
                            $_field['data']['name'] = $name . '[{key}]' . '[' . $_key . ']';
                            $_field['name'] = $name . '[' . self::_encodeKey($key) . ']' . '[' . $_key . ']';

                            $html .= self::_getField($_field);
                        }
                        $html .= "</div>";
                        $html .= "</div>";

                    }
                } elseif ($field != null && !empty($field)) {
                    foreach ($value as $key => $_value) {
                        $_field = $field;
                        $html .= "<div>";
                        $html .= sprintf('<input class="key" type="text" placeholder="key" value="%s" onchange="%s" >',
                            htmlspecialchars($key),
                            $on_change);

                        $_field['value'] = $_value;
                        $_field['data']['name'] = $name . '[{key}]';
                        $_field['name'] = $name . '[' . self::_encodeKey($key) . ']';
                        $html .= self::_getField($_field);

                        $html .= "</div>";
                    }
                }

                $html .= "<div class='group'>";
                $html .= sprintf('<input class="key" type="text" placeholder="key" onchange="%s">', $on_change);
                $html .= "<div>";

                if ($template != null && !empty($template)) {

                    foreach ($template as $key => $_field) {
                        $_field['name'] = $name . '[{key}]' . '[' . $key . ']';
                        $_field['disabled'] = true;
                        $html .= self::_getField($_field);
                    }
                } elseif ($field != null && !empty($field)) {
                    $field['name'] = $name . '[{key}]';
                    $field['disabled'] = true;
                    $html .= self::_getField($field);
                }
                $html .= "</div>";

                $html .= "</div>";
                break;
            case self::TYPE_GROUP:
                if ($template == null) {
                    $html .= "<div>Missing template</div>";
                } else {
                    $html .= "<div>";

                    if ($method == self::METHOD_SINGLE) {
                        foreach ($template as $key => $_field) {

                            $_field['name'] = $name . '[' . $key . ']';
                            $_field['value'] = $value[$key];
                            $html .= self::_getField($_field);
                        }
                    } elseif ($method == self::METHOD_MULTIPLE) {

                        $last_key = count($value) + 1;
                        foreach ($value as $key => $_value) {
                            $html .= "<div>";

                            foreach ($_value as $_key => $__value) {
                                $_field = $template[$_key];
                                $_field['name'] = $name . '[' . $key . ']' . '[' . $_key . ']';
                                $_field['value'] = $__value;
                                $html .= self::_getField($_field);
                            }
                            $html .= "</div>";

                        }
                        $html .= "<div>";
                        foreach ($template as $key => $_field) {
                            $_field['name'] = $name . '[' . $last_key . ']' . '[' . $key . ']';
                            $html .= self::_getField($_field);
                        }
                        $html .= "</div>";
                    }
                    $html .= "</div>";

                }
                break;

            default:
                if (!empty($values)) {
                    if ($markup == null || $markup == self::MARKUP_SELECT) {
                        $html .= sprintf('<select class="%1$s" id="%2$s" name="%3$s" %4$s %5$s %6$s %7$s>',
                            implode(' ', [$type, $method]),
                            $name,
                            $name . ($method == self::METHOD_MULTIPLE ? '[]' : ''),
                            $method == self::METHOD_MULTIPLE ? 'multiple="multiple"' : '',
                            $data_str,
                            $disabled_str,
                            $readonly_str
                        );
                        $html .= '<option>- Select -</option>';
                        $open_tag_select = true;
                    }

                    foreach ($values as $key => $_value) {
                        if ($markup == null || $markup == self::MARKUP_SELECT) {

                            $html .= sprintf('<option value="%s" %s>%s</option>',
                                htmlspecialchars($key),
                                (($key == $value) || (is_array($value) && in_array($key, $value))) ? 'selected' : '',
                                $_value
                            );
                        } elseif ($markup == self::MARKUP_CHECKBOX) {
                            if ($method == self::METHOD_MULTIPLE) {
                                $html .= sprintf('<div><label><input type="checkbox" name="%1$s" value="%2$s" %3$s %4$s %5$s %6$s>%7$s</label></div>',
                                    $name . ($method == self::METHOD_MULTIPLE ? '[]' : ''),
                                    htmlspecialchars($key),
                                    (($key == $value) || (is_array($value) && in_array($key, $value))) ? 'checked' : '',
                                    $data_str,
                                    $disabled_str,
                                    $readonly_str,
                                    $_value
                                );
                            } else {
                                $html .= sprintf('<div><label><input type="radio" name="%1$s" value="%2$s" %3$s %4$s %5$s %6$s>%7$s</label></div>',
                                    $name,
                                    htmlspecialchars($key),
                                    (($key == $value) || (is_array($value) && in_array($key, $value))) ? 'checked' : '',
                                    $data_str,
                                    $disabled_str,
                                    $readonly_str,
                                    $_value
                                );
                            }
                        }
                    }

                    if (isset($open_tag_select)) {
                        $html .= '</select>';
                    }

                } elseif ($method == self::METHOD_MULTIPLE) {
                    foreach ($value as $key => $_value) {
                        if (!empty($_value)) {
                            $html .= sprintf('<div class="group"><input name="%1$s" type="text" value="%2$s" %3$s %4$s %5$s>%6$s</div>',
                                $name . '[]',
                                htmlspecialchars($_value),
                                $data_str,
                                $disabled_str,
                                $readonly_str,
                                '<button class="button button-secondary" onclick="this.parentElement.remove()">X</button>'
                            );
                        }
                    }
                    $html .= sprintf('<div class="group" onclick="var e =this.querySelector(\'input[name]\'); e.disabled = false; e.focus()"><input name="%1$s" type="text" disabled></div>',
                        $name . '[]'
                    );
                    $html .= sprintf('<div class="group"><button class="button button-primary" type="button" onclick="%s">Add new</button></div>',
                        "var c = this.parentElement.previousSibling.cloneNode(true); c.children[0].value=''; this.parentElement.parentElement.insertBefore(c,this.parentElement);");
                } elseif ($method != self::METHOD_MULTIPLE) {
                    $html .= sprintf('<input id="%1$s" type="text" name="%1$s" value="%2$s" %3$s %4$s %5$s/>',
                        $name,
                        htmlspecialchars($value),
                        $data_str,
                        $disabled_str,
                        $readonly_str
                    );
                } else {
                    $html .= "Not handled";
                }
                break;
        }

        return $html;
    }

    public static function getFormData($parent, $method = 'post')
    {

        if ($method == 'post') {
            $fields = $_POST[$parent] ?? null;
        } elseif ($method == 'get') {
            $fields = $_GET[$parent] ?? null;
        } else {
            $fields = null;
        }

        if ($fields !== null) {
            $fields = self::decodeKeys($fields);
        }

        return $fields;

    }

    public static function decodeKeys(array $input)
    {
        $return = array();
        foreach ($input as $key => $value) {
            $key = self::_maybeDecodeKey($key);

            if (is_array($value)) {
                $value = self::decodeKeys($value);
            } elseif (is_string($value)) {
                $value = stripslashes($value);
                $value = self::_maybeBoolean($value);
            }
            $return[$key] = $value;
        }
        return $return;
    }
}