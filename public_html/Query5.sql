-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //
DROP PROCEDURE IF EXISTS Query5_Part1 //

CREATE PROCEDURE Query5_Part1 (IN country_name_in VARCHAR(25), IN industry_in VARCHAR(25), IN year_in SMALLINT)
BEGIN
    IF EXISTS (SELECT*
                FROM TradeFlows
                WHERE country_origin_name LIKE country_name_in AND 
                        sector LIKE industry_in AND year = year_in) THEN
        SELECT SUM(export_value) AS export_value
        FROM TradeFlows
        WHERE country_origin_name LIKE country_name_in AND 
                sector LIKE industry_in AND year = year_in;
    ELSE 
        SELECT 'ERROR: Invalid Country Name, Industry, and/or year' AS export_value;
    END IF;
END; //
DELIMITER ;

DELIMITER //
DROP PROCEDURE IF EXISTS Query5_Part2 //

CREATE PROCEDURE Query5_Part2 (IN country_name_in VARCHAR(25), IN industry_in VARCHAR(25), IN year_in SMALLINT)
BEGIN
    IF EXISTS (SELECT * FROM TradeFlows WHERE country_origin_name LIKE country_name_in AND 
                sector LIKE industry_in AND year = year_in) THEN
        SELECT country_dest_name, export_value
        FROM TradeFlows
        WHERE country_origin_name LIKE country_name_in AND 
                sector LIKE industry_in AND year = year_in
        ORDER BY export_value DESC
        LIMIT 5;
    ELSE 
        SELECT 'ERROR: Invalid Country Name, Industry, and/or year' AS country_dest_name;
    END IF;
END; //
DELIMITER ;
