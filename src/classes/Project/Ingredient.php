<?php

namespace Project;

/**
 * (Без названия)
 */
class Ingredient extends \ApCode\Billet\AbstractBillet implements \Interfaces\Data\UrlAssetInterface
{
    use History;
    use Meta;

    public function id()
    {
        return $this->data['id'] ?? null;
    }

    public function setId($value)
    {
        $this->data['id'] = $value;
        return $this;
    }

    public function typeId()
    {
        return $this->data['typeId'] ?? null;
    }

    public function type()
    {
        return IngredientType::getInstance($this->typeId());
    }

    public function setTypeId($value)
    {
        $this->data['typeId'] = $value;
        return $this;
    }

    public function title()
    {
        return $this->data['title'] ?? null;
    }

    public function setTitle($value)
    {
        $this->data['title'] = $value;
        return $this;
    }

    public function price()
    {
        return $this->data['price'] ?? null;
    }

    public function setPrice($value)
    {
        $this->data['price'] = $value;
        return $this;
    }

    public function name()
    {
        return self::class . '#' . $this->id();
    }

    public function urlAsset($key, $params = null)
    {
        $param = function($name, $default = null) use(&$params) { return isset($params[$name]) ? $params[$name] : $default; };
        $scope = $param('scope', Config()->get('urlAsset.scope', 'admin'));

        switch ("$scope:$key") {
            case 'admin:url.view' : return ShortUrl('@consultant/unknown/one/', ['record_id' => $this->id()]);

            case 'admin:link.view': return (new \ApCode\Html\Element\A($param('text', $this->name() ?: '(без названия)'), $this->urlAsset('url.view', $params), $param('title')));
        }

        trigger_error(sprintf('Unknown url asset %s in scope %s', $key, $scope), E_USER_WARNING);
        return null;
    }
}
