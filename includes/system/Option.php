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
        if ($str == '{{boolean_true}}') {
            return true;
        } elseif ($str == '{{boolean_false}}') {
            return false;
        }
        return $str;
    }

    private static function _getDataString(array $data): string
    {
        return implode(' ', array_map(
            function ($k, $v) {
                if (is_string($v)) {
                    $v = htmlspecialchars($v);
                }
                return 'data-' . $k . '="' . $v . '"';
            },
            array_keys($data), $data
        ));
    }


    /**
     * Array to html attributes string
     *
     * @param $data
     * @param string|null $parent
     * @return string
     */
    private static function _getAttrsString($data, ?string $parent = null): string
    {
        return implode(' ', array_map(
            function ($k, $v) use ($parent) {
                if (is_string($v)) {
                    $v = htmlspecialchars($v);
                    if ($parent == null && is_int($k)) {
                        return $v;
                    }
                    $k = ($parent ? $parent . '-' : '') . $k;
                    return $k . '="' . $v . '"';
                } elseif (is_array($v)) {
                    return self::_getAttrsString($v, $k);
                } elseif (empty($v)) {
                    $k = ($parent ? $parent . '-' : '') . $k;
                    return $k . '=""';
                } else {
                    $k = ($parent ? $parent . '-' : '') . $k;
                    return $k . '="' . json_encode($v) . '"';
                }

            },
            array_keys($data), $data
        ));
    }

    private static function _tagOpen(string $tag, ?array $attrs = null)
    {
        if ($attrs !== null) {
            $attrs = self::_getAttrsString($attrs);
        }

        $html = sprintf('<%s%s>',
            $tag,
            !empty($attrs) ? ' ' . $attrs : ''
        );

        return $html;
    }

    private static function _tagClose(string $tag)
    {
        $html = sprintf('</%s>', $tag);
        return $html;
    }

    private static function _tag(string $tag, ?string $content = '', ?array $attrs = [])
    {
        $html = self::_tagOpen($tag, $attrs);
        $html .= $content;
        $html .= self::_tagClose($tag);
        return $html;
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
        $data_str = self::_getDataString($data);

        $html = '';


        switch ($type) {
            case self::TYPE_BOOL:
                $html .= self::_tagOpen('input', ['value' => '{{boolean_false}}', 'type' => 'hidden', 'name' => $name]);

                $html .= self::_tagOpen('input',
                    [
                        'class' => implode(' ', [$type, $method]),
                        'id' => $name,
                        'value' => '{{boolean_true}}',
                        'type' => 'checkbox',
                        'name' => $name,
                        'data' => $data,
                        $value ? 'checked' : '',
                        $readonly_str,
                        $disabled_str
                    ]);
                break;
            case self::TYPE_OBJECT:
                $on_change = "var fields = this.parentElement.querySelectorAll('[name]'); for(var i=0; i<fields.length; i++){ var field= fields[i]; if(this.value!=null){field.removeAttribute('disabled')}; if(field.getAttribute('data-name') == null){ field.setAttribute('data-name',field.getAttribute('name')) } var attr = field.getAttribute('data-name'); attr = attr.replace('{key}','{{encode_key}}'+btoa(this.value)); fields[i].setAttribute('name',attr); }";

                if ($template != null && !empty($template)) {
                    foreach ($value as $key => $_value) {
                        $html .= self::_tagOpen('div', ['class' => 'group']);
                        $html .= self::_tag('button', 'X',
                            ['class' => 'remove', 'onclick' => 'this.parentElement.remove()']);

                        $html .= self::_tagOpen('input', [
                            'class' => 'key full',
                            'type' => 'text',
                            'placeholder' => 'key',
                            'value' => $key,
                            'onchange' => $on_change
                        ]);

                        $html .= self::_tagOpen('div', ['class' => 'group']);

                        foreach ($template as $_key => $_field) {

                            $_field['value'] = $_value[$_key] ?? null;
                            $_field['data']['name'] = $name . '[{key}]' . '[' . $_key . ']';
                            $_field['name'] = $name . '[' . self::_encodeKey($key) . ']' . '[' . $_key . ']';

                            $html .= self::_getField($_field);
                        }
                        $html .= self::_tagClose('div');
                        $html .= self::_tagClose('div');
                    }
                } elseif ($field != null && !empty($field)) {
                    foreach ($value as $key => $_value) {
                        $_field = $field;
                        $html .= self::_tagOpen('div', ['class' => 'group']);
                        $html .= self::_tag('button', 'X',
                            ['class' => 'remove', 'onclick' => 'this.parentElement.remove()']);

                        $html .= self::_tagOpen('input', [
                            'class' => 'key full',
                            'type' => 'text',
                            'placeholder' => 'key',
                            'value' => $key,
                            'onchange' => $on_change
                        ]);

                        $_field['value'] = $_value;
                        $_field['data']['name'] = $name . '[{key}]';
                        $_field['name'] = $name . '[' . self::_encodeKey($key) . ']';
                        $html .= self::_getField($_field);

                        $html .= self::_tagClose('div');
                    }
                }

                $html .= self::_tagOpen('div', ['class' => 'group']);
                $html .= self::_tagOpen('input',
                    ['class' => 'key full', 'type' => 'text', 'placeholder' => 'key', 'onchange' => $on_change]);

                $html .= self::_tagOpen('div', ['class' => 'group']);

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
                $html .= self::_tagClose('div');

                $html .= self::_tagClose('div');
                break;
            case self::TYPE_GROUP:
                if (!empty($template)) {
                    $html .= self::_tagOpen('div', ['class' => 'group']);

                    if ($method == self::METHOD_SINGLE) {
                        foreach ($template as $key => $_field) {

                            $_field['name'] = $name . '[' . $key . ']';
                            $_field['value'] = $value[$key];
                            $html .= self::_getField($_field);
                        }
                    } elseif ($method == self::METHOD_MULTIPLE) {

                        $last_key = count($value) + 1;
                        foreach ($value as $key => $_value) {
                            $html .= self::_tagOpen('div', ['class' => 'group']);

                            foreach ($_value as $_key => $__value) {
                                $_field = $template[$_key];
                                $_field['name'] = $name . '[' . $key . ']' . '[' . $_key . ']';
                                $_field['value'] = $__value;
                                $html .= self::_getField($_field);
                            }
                            $html .= self::_tagClose('div');

                        }
                        $html .= self::_tagOpen('div', ['class' => 'group']);
                        foreach ($template as $key => $_field) {
                            $_field['name'] = $name . '[' . $last_key . ']' . '[' . $key . ']';
                            $html .= self::_getField($_field);
                        }
                        $html .= self::_tagClose('div');
                    }
                    $html .= self::_tagClose('div');

                }
                break;

            default:
                if (!empty($values)) {
                    if ($markup == null || $markup == self::MARKUP_SELECT) {
                        $html .= self::_tagOpen('select', [
                            'class' => implode(' ', [$type, $method, 'full']),
                            'id' => $name,
                            'name' => $name . ($method == self::METHOD_MULTIPLE ? '[]' : ''),
                            $method == self::METHOD_MULTIPLE ? 'multiple' : '',
                            'data' => $data,
                            $disabled_str,
                            $readonly_str
                        ]);
                        $html .= self::_tag('option', '-- Select --');
                        //$html .= '<option>- Select -</option>';
                        $open_tag_select = true;
                    }

                    foreach ($values as $key => $_value) {
                        if ($markup == null || $markup == self::MARKUP_SELECT) {

                            $html .= self::_tag('option', $_value, [
                                'value' => $key,
                                (($key == $value) || (is_array($value) && in_array($key, $value))) ? 'selected' : ''
                            ]);

                        } elseif ($markup == self::MARKUP_CHECKBOX) {
                            if ($method == self::METHOD_MULTIPLE) {
                                $html .= self::_tag('div',
                                    self::_tag('label',
                                        self::_tagOpen('input', [
                                            'type' => 'checkbox',
                                            'name' => $name . ($method == self::METHOD_MULTIPLE ? '[]' : ''),
                                            'value' => $key,
                                            'data' => $data,
                                            (($key == $value) || (is_array($value) && in_array($key,
                                                        $value))) ? 'checked' : '',
                                            $disabled_str,
                                            $readonly_str,
                                        ]) . $_value
                                    )
                                    , ['class' => 'group']
                                );
                            } else {
                                $html .= self::_tag('div',
                                    self::_tag('label',
                                        self::_tagOpen('input', [
                                            'type' => 'radio',
                                            'name' => $name,
                                            'value' => $key,
                                            (($key == $value) || (is_array($value) && in_array($key,
                                                        $value))) ? 'checked' : '',
                                            'data' => $data,
                                            $disabled_str,
                                            $readonly_str,
                                        ]) . $_value
                                    )
                                    , ['class' => 'group']
                                );
                            }
                        }
                    }

                    if (isset($open_tag_select)) {
                        $html .= self::_tagClose('select');
                    }

                } elseif ($method == self::METHOD_MULTIPLE) {
                    $type = 'text';

                    if ($markup == self::MARKUP_NUMBER) {
                        $type = 'number';
                    }

                    foreach ($value as $key => $_value) {
                        if (!empty($_value)) {
                            $html .= self::_tag('div',
                                self::_tagOpen('input',
                                    [
                                        'name' => $name . '[]',
                                        'class' => 'full',
                                        'type' => $type,
                                        'value' => $_value,
                                        $disabled_str,
                                        $readonly_str,
                                    ]) . self::_tag('button', 'X',
                                    ['class' => 'remove', 'onclick' => 'this.parentElement.remove()']),
                                ['class' => 'group']
                            );
                        }
                    }
                    $html .= self::_tag('div',
                        self::_tagOpen('input',
                            ['name' => $name . '[]', 'class' => 'full', 'type' => 'text', 'disabled']),
                        [
                            'class' => 'group',
                            'onclick' => "var e=this.querySelector('input[name]'); e.disabled = false; e.focus()"
                        ]
                    );

                    $html .= self::_tag('div',
                        self::_tag('button', '+ Add new', [
                            'type' => 'button',
                            'class' => 'button button-primary',
                            'onclick' => "var c = this.parentElement.previousSibling.cloneNode(true); c.children[0].value=''; this.parentElement.parentElement.insertBefore(c,this.parentElement);"
                        ])
                        , ['class' => 'group']);


                } elseif ($method != self::METHOD_MULTIPLE) {
                    $html .= self::_tagOpen('input', [
                        'id' => $name,
                        'class' => 'full',
                        'type' => 'text',
                        'name' => $name,
                        'value' => $value,
                        'data' => $data,
                        $disabled_str,
                        $readonly_str
                    ]);
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

    private static function printArrayList($array)
    {
        echo '<ul class="' . Bootstrap::SLUG . '-admin-nested-fields">';

        foreach ($array as $k => $v) {
            if (is_array($v)) {
                echo '<li class="label">' . ucfirst($k) . "</li>";
                self::printArrayList($v);
                continue;
            }

            echo "<li>" . $v . "</li>";
        }

        echo "</ul>";
    }

    private static function arrayWalkWithRoute(
        array &$arr,
        callable $callback,
        array $route = []
    ): void {
        foreach ($arr as $key => &$val) {
            $_route = $route;
            $_route[] = $key;
            if (is_array($val)) {
                self::arrayWalkWithRoute($val, $callback, $_route);
            } else {
                call_user_func_array($callback, [$key, &$val, $_route]);
            }
        }
    }

    public static function printForm($parent, $options)
    {

        $form_data = Option::getFormData($parent);

        if ($form_data) {
            foreach ($form_data as $key => $field) {
                Bootstrap::setOption($key, $field);
            }
        }

        $_fields = [];

        static::arrayWalkWithRoute($options, function ($key, $item, $route) use (&$_fields) {
            if ($item instanceof Option) {
                array_pop($route);
                $label = $item->getParam('label', $item->getName());
                $description = $item->getParam('description', null);
                $field = $item->getField();
                $html = sprintf(
                    '<div class="section"><div class="label">%s</div><div class="field">%s</div>%s</div>',
                    $label,
                    $field,
                    $description != null ? sprintf('<div class="description">%s</div>', $description) : ''
                );
                $temp = &$_fields;
                foreach ($route as $key) {
                    $temp = &$temp[$key];
                }
                $temp[] = $html;
                unset($temp);
            }
        });

        ?>
        <div class="wrap <?php echo Bootstrap::SLUG; ?>-wrap">
            <h1>i18n Configuration</h1>

            <form method="post" action="">
                <?php self::printArrayList($_fields); ?>
                <input type="hidden" name="<?php echo Bootstrap::SLUG; ?>-form" value="1">
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}