<?php
/**
 * ChargeAfter
 *
 * @category    Payment Gateway
 * @package     Chargeafter_Payment
 * @copyright   Copyright (c) 2021 ChargeAfter.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      taras@lagan.com.ua
 */

namespace Chargeafter\Payment\Test\Unit\Helper;

use Chargeafter\Payment\Helper\ApiHelper;
use Magento\Payment\Gateway\ConfigInterface;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class ApiHelperTest extends TestCase
{
    private $config;
    private $helper;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        $this->config = $this->createMock(ConfigInterface::class);
        $this->helper = new ApiHelper($this->config);
    }

    /**
     * @param string $environment
     * @param bool $expected
     * @dataProvider  dataProviderTestIsSandboxMode
     */
    public function testIsSandboxMode(string $environment, bool $expected)
    {
        $this->config->expects($this->once())
            ->method('getValue')
            ->with('environment')
            ->willReturn($environment);

        $actual = $this->helper->isSandboxMode(null);

        self::assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    public function dataProviderTestIsSandboxMode()
    {
        return[
            [
                'environment' => 'sandbox',
                'expected' => true
            ],
            [
                'environment' => 'production',
                'expected' => false
            ]
        ];
    }

    /**
     * @param string $environment
     * @param string $expected
     * @dataProvider dataProviderTestGetApiUrl
     */
    public function testGetApiUrl(string $environment, string $expected)
    {
        $this->config->expects($this->once())
            ->method('getValue')
            ->with('environment')
            ->willReturn($environment);

        $actual = $this->helper->getApiUrl();

        self::assertSame($expected, $actual);
    }

    /**
     * @return string[][]
     */
    public function dataProviderTestGetApiUrl(): array
    {
        return[
            [
                'environment'=>'sandbox',
                'expected'=>'https://api-sandbox.ca-dev.co/v2'
            ],
            [
                'environment'=>'production',
                'expected'=>'https://api.chargeafter.com/v2'
            ]
        ];
    }

    /**
     * @param string $environment
     * @param string $expected
     * @dataProvider dataProviderTestGetApiUrlWithoutVersion
     */
    public function testGetApiUrlWithoutVersion(string $environment, string $expected)
    {
        $this->config->expects($this->once())
            ->method('getValue')
            ->with('environment')
            ->willReturn($environment);

        $actual = $this->helper->getApiUrl(null, null, true);

        self::assertSame($expected, $actual);
    }

    /**
     * @return string[][]
     */
    public function dataProviderTestGetApiUrlWithoutVersion(): array
    {
        return[
            [
                'environment'=>'sandbox',
                'expected'=>'https://api-sandbox.ca-dev.co'
            ],
            [
                'environment'=>'production',
                'expected'=>'https://api.chargeafter.com'
            ]
        ];
    }

    /**
     * @param string $environment
     * @param string $expected
     * @dataProvider dataProviderTestGetCdnUrl
     */
    public function testGetCdnUrl(string $environment, string $expected)
    {
        $this->config->expects($this->once())
            ->method('getValue')
            ->with('environment')
            ->willReturn($environment);

        $actual = $this->helper->getCdnUrl();

        self::assertSame($expected, $actual);
    }

    public function dataProviderTestGetCdnUrl(): array
    {
        return[
            [
                'environment'=>'sandbox',
                'expected'=>'https://cdn-sandbox.ca-dev.co'
            ],
            [
                'environment'=>'production',
                'expected'=>'https://cdn.chargeafter.com'
            ]
        ];
    }

    /**
     * @param string $environment
     * @param string $expected
     * @dataProvider dataProviderTestGetPrivateKey
     */
    public function testGetPrivateKey(string $environment, string $expected)
    {
        $this->config->expects($this->exactly(2))
            ->method('getValue')
            ->willReturnMap([
                ['environment', null, $environment],
                ['sandbox_private_key', null, 'sandbox_private_key'],
                ['production_private_key', null, 'production_private_key']
            ]);

        $actual = $this->helper->getPrivateKey();

        self::assertSame($expected, $actual);
    }

    /**
     * @param string $environment
     * @param string $expected
     * @dataProvider dataProviderTestGetStoreId
     */
    public function testGetStoreId(string $environment, string $expected)
    {
        $this->config->expects($this->exactly(2))
            ->method('getValue')
            ->willReturnMap([
                ['environment', null, $environment],
                ['sandbox_store_id', null, 'sandbox_store_id'],
                ['production_store_id', null, 'production_store_id']
            ]);

        $actual = $this->helper->getStoreId();

        self::assertSame($expected, $actual);
    }

    public function dataProviderTestGetPrivateKey(): array
    {
        return [
            [
                'environment'=>'sandbox',
                'expected'=>'sandbox_private_key'
            ],
            [
                'environment'=>'production',
                'expected'=>'production_private_key'
            ]
        ];
    }

    public function dataProviderTestGetStoreId(): array
    {
        return [
            [
                'environment'=>'sandbox',
                'expected'=>'sandbox_store_id'
            ],
            [
                'environment'=>'production',
                'expected'=>'production_store_id'
            ]
        ];
    }

    /**
     * @param string $environment
     * @param string $expected
     * @dataProvider dataProviderTestGetPublicKey
     */
    public function testGetPublicKey(string $environment, string $expected)
    {
        $this->config->expects($this->exactly(2))
            ->method('getValue')
            ->willReturnMap([
                ['environment', null, $environment],
                ['sandbox_public_key', null, 'sandbox_public_key'],
                ['production_public_key', null, 'production_public_key']
            ]);

        $actual = $this->helper->getPublicKey();

        self::assertSame($expected, $actual);
    }

    public function dataProviderTestGetPublicKey(): array
    {
        return [
            [
                'environment'=>'sandbox',
                'expected'=>'sandbox_public_key'
            ],
            [
                'environment'=>'production',
                'expected'=>'production_public_key'
            ]
        ];
    }

    /**
     * @param string $transactionType
     * @param string $expected
     * @dataProvider  dataProviderTestGetTransactionType
     */
    public function testGetTransactionType(string $transactionType, string $expected)
    {
        $this->config->expects($this->once())
            ->method('getValue')
            ->with('transaction_type')
            ->willReturn($transactionType);

        $actual = $this->helper->getTransactionType(null);

        self::assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    public function dataProviderTestGetTransactionType()
    {
        return[
            [
                'environment' => 'authorization',
                'expected' => 'authorization'
            ],
            [
                'environment' => 'capture',
                'expected' => 'capture'
            ]
        ];
    }

    /**
     * @param int|null $shippingEqualsBilling
     * @param bool $expected
     * @dataProvider  dataProviderTestIsShippingEqualsBilling
     */
    public function testIsShippingEqualsBilling($shippingEqualsBilling, bool $expected)
    {
        $this->config->expects($this->once())
            ->method('getValue')
            ->with('bill_to_equal_ship_to')
            ->willReturn($shippingEqualsBilling);

        $actual = $this->helper->shouldBeShippingEqualsBilling(null);

        self::assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    public function dataProviderTestIsShippingEqualsBilling()
    {
        return[
            [
                'bill_to_equal_ship_to' => 0,
                'expected' => false
            ],
            [
                'bill_to_equal_ship_to' => 1,
                'expected' => true
            ],
            [
                'bill_to_equal_ship_to' => null,
                'expected' => false
            ]
        ];
    }

    /**
     * @param int|null $consumerDataUpdateActivate
     * @param bool $expected
     *
     * @dataProvider dataProviderTestIsConsumerDataUpdateActivate
     */
    public function testIsConsumerDataUpdateActivate($consumerDataUpdateActivate, bool $expected)
    {
        $this->config->expects($this->once())
            ->method('getValue')
            ->with('customer_data_update_active')
            ->willReturn($consumerDataUpdateActivate);

        $actual = $this->helper->shouldUpdateConsumerData(null);

        self::assertEquals($expected, $actual);
    }

    /**
     * @return array[]
     */
    public function dataProviderTestIsConsumerDataUpdateActivate()
    {
        return [
            [
                'customer_data_update_active' => 1,
                'expected' => true
            ],
            [
                'customer_data_update_active' => 0,
                'expected' => false
            ],
            [
                'customer_data_update_active' => null,
                'expected' => false
            ]
        ];
    }
}
