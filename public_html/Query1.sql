-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //
DROP PROCEDURE IF EXISTS Query1 //

CREATE PROCEDURE Query1 (IN year_in SMALLINT)
BEGIN
    IF EXISTS (SELECT *
                FROM Climate AS C JOIN Economic_Complexity AS E 
                ON (C.country_name LIKE E.country_name AND C.year = E.year)
                JOIN TradeFlows AS T 
                ON (E.country_name LIKE T.country_origin_name AND
                E.year = T.year)
                WHERE C.year = year_in
                GROUP BY C.country_name) THEN
        SELECT C.country_name, C.co2_emissions, E.econ_comp_index, T.sector, MAX(T.export_value) AS export_value
        FROM Climate AS C JOIN Economic_Complexity AS E 
        ON (C.country_name LIKE E.country_name AND C.year = E.year)
        JOIN TradeFlows AS T 
        ON (E.country_name LIKE T.country_origin_name AND E.year = T.year)
        WHERE C.year = year_in
        GROUP BY C.country_name;
    ELSE 
        SELECT 'ERROR: Invalid Year' AS country_name;
    END IF;
END; //
DELIMITER ;
