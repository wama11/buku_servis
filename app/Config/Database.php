<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array<string, mixed>
     */
    // public array $default = [
    //     'DSN'          => '',
    //     'hostname'     => 'localhost',
    //     'username'     => '',
    //     'password'     => '',
    //     'database'     => '',
    //     'DBDriver'     => 'MySQLi',
    //     'DBPrefix'     => '',
    //     'pConnect'     => false,
    //     'DBDebug'      => true,
    //     'charset'      => 'utf8mb4',
    //     'DBCollat'     => 'utf8mb4_general_ci',
    //     'swapPre'      => '',
    //     'encrypt'      => false,
    //     'compress'     => false,
    //     'strictOn'     => false,
    //     'failover'     => [],
    //     'port'         => 3306,
    //     'numberNative' => false,
    //     'foundRows'    => false,
    //     'dateFormat'   => [
    //         'date'     => 'Y-m-d',
    //         'datetime' => 'Y-m-d H:i:s',
    //         'time'     => 'H:i:s',
    //     ],
    // ];

    //    /**
    //     * Sample database connection for SQLite3.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'database'    => 'database.db',
    //        'DBDriver'    => 'SQLite3',
    //        'DBPrefix'    => '',
    //        'DBDebug'     => true,
    //        'swapPre'     => '',
    //        'failover'    => [],
    //        'foreignKeys' => true,
    //        'busyTimeout' => 1000,
    //        'synchronous' => null,
    //        'dateFormat'  => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for Postgre.
    //     *
    //     * @var array<string, mixed>
    //     */
    public array $default = [
        'DSN' => '',
        'hostname' => '172.17.23.39',
        'username' => 'app_dbbs',
        'password' => 'bukserappDB!',
        'database' => 'bukuservis',
        'schema' => 'public',
        'DBDriver' => 'Postgre',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'swapPre' => '',
        'failover' => [],
        'port' => 5432,
        'dateFormat' => [
            'date' => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time' => 'H:i:s',
        ],
    ];

    public array $robotic = [
        'DSN' => '',
        'hostname' => '172.17.20.194',
        'username' => 'api_wa',
        'password' => '4lert@wa20204',
        'database' => 'DATARMS',
        'schema' => 'public',
        'DBDriver' => 'Postgre',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'swapPre' => '',
        'failover' => [],
        'port' => 5432,
        'dateFormat' => [
            'date' => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time' => 'H:i:s',
        ],
    ];

    public array $ho = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => '172.17.20.22',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        // 'database' => 'PB_HO',
        'database' => 'PB_DEV_HO',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $devDC = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => '172.17.20.68',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        // 'database' => 'PB_HO',
        'database' => 'PB_DEV_DC',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $JKT = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => 'rms10.planetban.co.id',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        'database' => 'PB_DC',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $TNG = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => 'rms12.planetban.co.id',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        'database' => 'PB_DC',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $PLG = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => 'rms15.planetban.co.id',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        'database' => 'PB_DC',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $SBY = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => 'rms20.planetban.co.id',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        'database' => 'PB_DC',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $SMG = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => 'rms21.planetban.co.id',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        'database' => 'PB_DC',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $DPS = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => 'rms22.planetban.co.id',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        'database' => 'PB_DC',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $BDG = [
        'DSN' => '',
        // 'hostname' => '172.17.20.84',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        // 'username' => '4p1-1men',
        // 'password' => 'aP1@Imen01',
        'hostname' => 'rms11.planetban.co.id',
        'username' => 'sa',
        'password' => 'Astrophytum1598',
        // 'username' => 'sa',
        // 'password' => 'Astrophytum1598',
        'database' => 'PB_DC',
        // 'database' => 'PB_HO',
        'DBDriver' => 'SQLSRV',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 1433,
    ];

    public array $point = [
        'DSN' => '',
        'hostname' => '172.17.20.24',
        'username' => 'angga_it',
        'password' => 'P@ssw0rd.1',
        'database' => 'db_point',
        'schema' => 'public',
        'DBDriver' => 'Postgre',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'swapPre' => '',
        'failover' => [],
        'port' => 5432,
        'dateFormat' => [
            'date' => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time' => 'H:i:s',
        ],
    ];

    //    /**
    //     * Sample database connection for SQLSRV.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'dbo',
    //        'DBDriver'   => 'SQLSRV',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'encrypt'    => false,
    //        'failover'   => [],
    //        'port'       => 1433,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for OCI8.
    //     *
    //     * You may need the following environment variables:
    //     *   NLS_LANG                = 'AMERICAN_AMERICA.UTF8'
    //     *   NLS_DATE_FORMAT         = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_FORMAT    = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS'
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => 'localhost:1521/XEPDB1',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'DBDriver'   => 'OCI8',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'AL32UTF8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    /**
     * This database connection is used when running PHPUnit database tests.
     *
     * @var array<string, mixed>
     */
    public array $tests = [
        'DSN' => '',
        'hostname' => '127.0.0.1',
        'username' => '',
        'password' => '',
        'database' => ':memory:',
        'DBDriver' => 'SQLite3',
        'DBPrefix' => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect' => false,
        'DBDebug' => true,
        'charset' => 'utf8',
        'DBCollat' => '',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'dateFormat' => [
            'date' => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time' => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
