<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of the Pago46 library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5, 7
 *
 * LICENSE: This source file is subject to the MIT license that is available
 * through the world-wide-web at the following URI:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category Src
 * @package  Normeno\Pago46
 * @author   Nicolas Ormeno <ni.ormeno@gmail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/normeno/pago46
 */

namespace Normeno\Pago46;

use Normeno\Pago46\Caller;

/**
 * Pago46 Class
 *
 * @category Src
 * @package  Normeno\Pago46
 * @author   Nicolas Ormeno <ni.ormeno@gmail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/normeno/gjson
 */
class Pago46
{
    /**
     * Merchant data [key, secret]
     *
     * @var array
     */
    private $merchant = [];

    /**
     * Request data [method, path]
     *
     * @var array
     */
    private $request = [];

    /**
     * Environment
     *
     * @var string
     */
    private $env = 'production';

    /**
     * Header
     *
     * @var array
     */
    private $header = [];

    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {
        $this->merchant = [
            'key'       =>  getenv('MERCHANT_KEY'),
            'secret'    =>  getenv('MERCHANT_SECRET')
        ];
    }

    /**
     * Get transactions
     *
     * @return array
     */
    public function getOrders()
    {
        $this->request = ['method' => 'GET', 'path' => '%2Fmerchant%2Forders%2F'];
        $this->header = Caller::setHeader($this->merchant, $this->request, false);
        $api = Caller::call('merchant/orders/', $this->request['method'], false);

        return $api;
    }

    /**
     * Get order by orderId
     *
     * @param $id
     *
     * @return array
     */
    public function getOrderByID($id)
    {
        $this->request = ['method' => 'GET', 'path' => "%2Fmerchant%2Forder%2F{$id}"];
        $this->header = $this->setHeader(false);
        $api = $this->callApi("merchant/order/{$id}", $this->request['method'], false);

        return $api;
    }

    /**
     * Get order by notificationId
     *
     * @param $id
     *
     * @return array
     */
    public function getOrderByNotificationID($id)
    {
        $this->request = ['method' => 'GET', 'path' => "%2Fmerchant%2Forder%2F{$id}"];
        $this->header = $this->setHeader(false);
        $api = $this->callApi("merchant/notification/{$id}", $this->request['method'], false);

        return $api;
    }

    /**
     * Create a new order
     *
     * @param array $order
     *
     * @return array
     */
    public function newOrder($order)
    {
        $this->request = ['method' => 'POST', 'path' => '%2Fmerchant%2Forders%2F'];

        $concatenateParams = '';

        foreach ($order as $k => $v) {
            $value = urlencode($v);
            $concatenateParams .= "&{$k}={$value}";
        }

        $this->header = $this->setHeader($concatenateParams);

        $api = $this->callApi("merchant/orders/", $this->request['method'], $order);

        return $api;
    }
}
