-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //
DROP PROCEDURE IF EXISTS Query3 //

CREATE PROCEDURE Query3 (IN initial_year_in SMALLINT, IN end_year_in SMALLINT)
BEGIN
    IF EXISTS (SELECT *
                FROM TradeFlows AS T JOIN GDP AS G ON (T.country_origin_name LIKE G.country_name AND T.year = G.year)
                JOIN US_Leaders AS U ON (U.start_year = G.year)
                WHERE (T.year >= initial_year_in AND T.year <= end_year_in)
                GROUP BY T.year, country_name
                ORDER BY gdp DESC, SUM(export_value) DESC
                LIMIT 5) THEN
        SELECT country_name, leader_name, sector, export_value, gdp, U.start_year, U.end_year
        FROM TradeFlows AS T JOIN GDP AS G ON (T.country_origin_name LIKE G.country_name AND T.year = G.year)
        JOIN US_Leaders AS U ON (U.start_year = G.year)
        WHERE (T.year >= initial_year_in AND T.year <= end_year_in)
        GROUP BY T.year, country_name
        ORDER BY gdp DESC, SUM(export_value) DESC
        LIMIT 5;
    ELSE 
        SELECT 'ERROR: Invalid Year Value(s)' AS country_origin_name;
    END IF;
END; //
DELIMITER ;
