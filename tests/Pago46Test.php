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
 * @category Test
 * @package  Normeno\Pago46\Test
 * @author   Nicolas Ormeno <ni.ormeno@gmail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/normeno/pago46
 */

namespace Normeno\Pago46\Tests;

use Normeno\Pago46\Pago46;

/**
 * Tests for Pago46
 *
 * @category Test
 * @package  Normeno\Pago46\Test
 * @author   Nicolas Ormeno <ni.ormeno@gmail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/normeno/pago46
 */
class Pago46Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Pago46 instance
     *
     * @var Pago46
     */
    private $pago46;

    /**
     * Pago46Test constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->pago46 = new Pago46();
    }

    /**
     * Test that true does in fact equal true
     */
    public function testGetOrders()
    {
        $orders     = $this->pago46->getOrders();
        $response   = is_array($orders) || is_object($orders) ? true : false;

        $this->assertTrue($response);
    }
}
