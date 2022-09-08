-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //

DROP PROCEDURE IF EXISTS TopImportersLessEconomicallyComplex //

CREATE PROCEDURE TopImportersLessEconomicallyComplex(IN Country VARCHAR(25), IN curr_year INT)
BEGIN
   IF EXISTS(SELECT *
               FROM TradeFlows 
               WHERE year = curr_year AND country_dest_name = Country AND 
               country_origin_name IN (SELECT country_name from Economic_Complexity where year = curr_year AND econ_comp_index < (SELECT econ_comp_index from Economic_Complexity where country_name = Country AND year = curr_year LIMIT 1))
               GROUP BY country_origin_name
               ORDER BY SUM(export_value) DESC
               LIMIT 3) THEN
      SELECT country_origin_name, SUM(export_value) AS 'Total Imports'
      from TradeFlows 
      where year = curr_year AND country_dest_name = Country AND 
      country_origin_name IN (SELECT country_name from Economic_Complexity where year = curr_year AND econ_comp_index < (SELECT econ_comp_index from Economic_Complexity where country_name = Country AND year = curr_year LIMIT 1))
      GROUP BY country_origin_name
      ORDER BY SUM(export_value) DESC
      LIMIT 5;
   ELSE
      SELECT 'ERROR: No Importers Lower than the given country in that year' AS country_origin_name;
   END IF;
END; //

DELIMITER ;
