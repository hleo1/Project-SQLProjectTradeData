-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //
DROP PROCEDURE IF EXISTS Query2 //

CREATE PROCEDURE Query2 (IN country_name_in VARCHAR(25), IN industry VARCHAR(25))
BEGIN
    IF EXISTS (SELECT *
                FROM TradeFlows AS T JOIN GDP AS G ON (T.country_origin_name LIKE G.country_name AND T.year = G.year)
                JOIN Climate AS C ON (G.country_name LIKE C.country_name AND G.year = C.year)
                WHERE G.country_name LIKE country_name_in AND sector LIKE industry
                GROUP BY G.year) THEN
        SELECT G.country_name, SUM(T.export_value) AS export_value, G.gdp, co2_emissions, G.year
        FROM TradeFlows AS T JOIN GDP AS G ON (T.country_origin_name LIKE G.country_name AND T.year = G.year)
        JOIN Climate AS C ON (G.country_name LIKE C.country_name AND G.year = C.year)
        WHERE G.country_name LIKE country_name_in AND sector LIKE industry
        GROUP BY G.year;
    ELSE 
        SELECT 'ERROR: Invalid Country Name and/or industry' AS country_name;
    END IF;
END; //
DELIMITER ;
