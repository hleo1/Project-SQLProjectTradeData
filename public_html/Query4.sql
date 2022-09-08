-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //
DROP PROCEDURE IF EXISTS Query4 //

CREATE PROCEDURE Query4 (IN year_in SMALLINT)
BEGIN
    IF EXISTS (SELECT G.country_name, G.gdp, SUM(T.export_value) AS export_value
                FROM GDP AS G JOIN TradeFlows AS T
                ON G.country_name LIKE T.country_origin_name AND G.year = T.year
                WHERE G.year = 2016
                GROUP BY country_name
                ORDER BY country_name ASC) THEN
        SELECT G.country_name, G.gdp, SUM(T.export_value) AS export_value
        FROM GDP AS G JOIN TradeFlows AS T
        ON G.country_name LIKE T.country_origin_name AND G.year = T.year
        WHERE G.year = 2016
        GROUP BY country_name
        ORDER BY country_name ASC;
    ELSE 
        SELECT 'ERROR: Invalid Year' AS country_name;
    END IF;
END; //
DELIMITER ;
