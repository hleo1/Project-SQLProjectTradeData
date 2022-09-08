-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //
DROP PROCEDURE IF EXISTS ShowCountryGDP //
CREATE PROCEDURE ShowCountryGDP(IN country VARCHAR(25), IN start_year INT, IN end_year INT)
BEGIN
   IF EXISTS(SELECT *
               FROM GDP
               WHERE (year >= start_year AND year <= end_year) AND (country_name LIKE country)) THEN
      SELECT *
      FROM GDP
      WHERE (year >= start_year AND year <= end_year) AND (country_name LIKE country);
   ELSE
      SELECT 'ERROR: Country not in database';
   END IF;
END; //
DELIMITER ;

DELIMITER //
DROP PROCEDURE IF EXISTS ShowCountryImports //
CREATE PROCEDURE ShowCountryImports(IN country VARCHAR(25), IN start_year INT, IN end_year INT)
BEGIN
   IF EXISTS(SELECT year, SUM(export_value) AS 'Total Imports'
               FROM TradeFlows 
               WHERE country_dest_name = country and year >= start_year and year <= end_year GROUP BY year) THEN
      SELECT year, SUM(export_value) AS 'Total Imports'
      FROM TradeFlows 
      WHERE country_dest_name = country and year >= start_year and year <= end_year GROUP BY year;
   ELSE
      SELECT 'ERROR: Country not in database';
   END IF;
END; //
DELIMITER ;


DELIMITER //
DROP PROCEDURE IF EXISTS ShowCountryExports //
CREATE PROCEDURE ShowCountryExports(IN country VARCHAR(25), IN start_year INT, IN end_year INT)
BEGIN
   IF EXISTS(SELECT year, SUM(export_value) AS 'Total Exports'
               FROM TradeFlows 
               WHERE country_origin_name = country and year >= start_year and year <= end_year GROUP BY year) THEN
      SELECT year, SUM(export_value) AS 'Total Exports'
      from TradeFlows 
      where country_origin_name = country and year >= start_year and year <= end_year GROUP BY year;
   ELSE
      SELECT 'ERROR: Country not in database';
   END IF;
END; //
DELIMITER ;
