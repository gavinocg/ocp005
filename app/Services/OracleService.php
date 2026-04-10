<?php

namespace App\Services;

class OracleService
{
    private $connection;
    private array $config;

    public function __construct()
    {
        $this->config = [
            'host' => '192.168.1.108',
            'port' => '1521',
            'service_name' => 'rp',
            'user' => 'consulta',
            'password' => 'francisco',
        ];
    }

    public function connect(): bool
    {
        if (!function_exists('oci_connect')) {
            error_log("Oracle: Extension oci8 no esta instalada");
            return false;
        }

        $connectionString = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST={$this->config['host']})(PORT={$this->config['port']}))(CONNECT_DATA=(SERVICE_NAME={$this->config['service_name']})))";

        $this->connection = @oci_connect(
            $this->config['user'],
            $this->config['password'],
            $connectionString,
            'AL32UTF8'
        );

        if (!$this->connection) {
            $error = oci_error();
            error_log("Oracle Connection Error: " . ($error['message'] ?? 'Unknown error'));
            return false;
        }

        return true;
    }

    public function disconnect(): void
    {
        if ($this->connection) {
            oci_close($this->connection);
            $this->connection = null;
        }
    }

    public function isConnected(): bool
    {
        return $this->connection !== null;
    }

    public function query(string $sql): array
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $statement = oci_parse($this->connection, $sql);

        if (!$statement) {
            $error = oci_error($this->connection);
            error_log("Oracle Query Error: " . ($error['message'] ?? 'Unknown error'));
            return [];
        }

        $executeResult = @oci_execute($statement);

        if (!$executeResult) {
            $error = oci_error($statement);
            error_log("Oracle Execute Error: " . ($error['message'] ?? 'Unknown error'));
            oci_free_statement($statement);
            return [];
        }

        $results = [];
        while ($row = oci_fetch_assoc($statement)) {
            $results[] = $row;
        }

        oci_free_statement($statement);

        return $results;
    }

    public function getTramites(): array
    {
        $sql = "SELECT * FROM (
            SELECT t.cod_tramsec tramite, 
                t.fecha_reg,
                (SUM(ta.valor)-SUM(ta.valor_dscto) + NVL((SELECT SUM(tr.valor*tr.cant) FROM tramite_rubro tr WHERE tr.cod_tram=t.cod_tram), 0)) valor,
                p.tip_identificacion, 
                p.identificacion,
                SUBSTR(
                    TRIM(
                        NVL(p.pri_apellido, '') || ' ' || NVL(p.seg_apellido, '') || ' ' || 
                        NVL(p.pri_nombre, '') || ' ' || NVL(p.seg_nombre, '')
                    ), 
                    1, 30
                ) AS nombre
            FROM tramite t 
            INNER JOIN tramite_acto ta ON t.cod_tram=ta.cod_tram    
            INNER JOIN persona p ON t.cod_persol=p.cod_per 
            INNER JOIN acto a ON ta.cod_act=a.cod_act
            LEFT JOIN tramite_caja tc ON tc.cod_tram=t.cod_tram
            WHERE t.cod_tram NOT IN ( 
                SELECT tol.cod_tram
                FROM traact_obs_legal tol
                WHERE tol.estado='OBSERVADO'
                GROUP BY tol.cod_tram
            )
            AND t.cod_pro = 'INSCRIPCION'
            AND ( EXTRACT(YEAR FROM t.fecha_reg) = EXTRACT(YEAR FROM SYSDATE) ) 
            AND ta.f_inscripcion IS NULL AND ta.no_inscripcion IS NULL
            AND t.estado NOT IN ('INACTIVO', 'ANULADO')
            AND tc.estado IS NULL    
            GROUP BY t.cod_tramsec, t.cod_tram, tip_identificacion, p.identificacion, p.pri_nombre, p.pri_apellido, p.celular, 
                p.telefono, p.email, SUBSTR(
                    TRIM(
                        NVL(p.pri_apellido, '') || ' ' || NVL(p.seg_apellido, '') || ' ' || 
                        NVL(p.pri_nombre, '') || ' ' || NVL(p.seg_nombre, '')
                    ), 
                    1, 30
                ), 
                NVL(TRIM(p.telefono),'NO'), NVL(TRIM(p.celular),'NO'), 
                NVL(TRIM(p.email),'NO'), t.fecha_reg, tc.estado
            ORDER BY valor DESC
        ) WHERE valor > 0";

        return $this->query($sql);
    }
}