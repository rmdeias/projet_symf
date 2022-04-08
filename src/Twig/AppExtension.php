<?php
/**
 * Created by PhpStorm.
 * User: rmdei
 * Date: 08/04/2022
 * Time: 11:42
 */

namespace App\Twig;


use Symfony\Component\Intl\Locales;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $_localeCodes;
    private $_locales;

    public function __construct($locales, $defaultLocale)
    {
        $localeCodes = explode('|', $locales);
        sort($localeCodes);
        $this->_localeCodes = $localeCodes;
    }

    public function getFunctions(){
        return [
            new TwigFunction('locales', [$this, 'getLocales']),
        ];
    }

    public function getLocales()
    {
        $this->_locales = [];
        foreach($this->_localeCodes as $localeCode){
            $this->_locales[]=
                [
                    'code' => $localeCode,
                    'name' => Locales::getName($localeCode)
                ];
        }
        return $this-> _locales;
    }

}
