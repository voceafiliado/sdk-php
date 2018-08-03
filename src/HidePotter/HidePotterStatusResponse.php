<?php namespace VCA\Sdk\HidePotter;

use Carbon\Carbon;
use VCA\Sdk\ResponseObject;

/**
 * Class HidePotterStatusResponse
 * @package VCA\Sdk\HidePotter
 *
 * @property string $type
 * @property string|null $redirect_url
 * @property string|null $fbookPixel
 * @property HidePotterStatusBlockResponse|null $block
 */
class HidePotterStatusResponse extends ResponseObject
{
    const typeNotFound     = 'not-found';
    const typeInactive     = 'link-inactive';
    const typeUserInactive = 'user-inactive';
    const typeSafePage     = 'safe-page';
    const typeSalesFunnel  = 'sales-funnel';

    /**
     * Retorna o usuario
     *
     * @return null|HidePotterStatusBlockResponse
     */
    protected function block($value)
    {
        return new HidePotterStatusBlockResponse($this->client, $value);
    }

    /**
     * @return bool
     */
    public function isNotFound()
    {
        return ($this->type == self::typeNotFound);
    }
    /**
     * @return bool
     */
    public function isInactive()
    {
        return ($this->type == self::typeInactive);
    }
    /**
     * @return bool
     */
    public function isUserInactive()
    {
        return ($this->type == self::typeUserInactive);
    }
    /**
     * @return bool
     */
    public function isSafePage()
    {
        return ($this->type == self::typeSafePage);
    }
    /**
     * @return bool
     */
    public function isSalesFunnel()
    {
        return ($this->type == self::typeSalesFunnel);
    }

    /**
     * @return mixed|string
     */
    public function getPageContent()
    {
        $content = $this->pageContent;
        if ($content != '') {
            $content = base64_decode($content);
        }

        return $content;
    }
}