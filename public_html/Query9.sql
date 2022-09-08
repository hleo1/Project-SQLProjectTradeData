-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //

DROP PROCEDURE IF EXISTS ShowUSTopExportGrowths //

CREATE PROCEDURE ShowUSTopExportGrowths(IN start_year INT, IN end_year INT)
BEGIN
   WITH CurrentYearExportData AS 
                (SELECT sector, SUM(export_value) AS CurrentYearExports
                FROM TradeFlows
                WHERE country_origin_name = "United States" AND year = start_year
                GROUP BY sector),
             NextYearExportData AS 
                (SELECT sector, SUM(export_value) AS NextYearExports
                FROM TradeFlows
                WHERE country_origin_name = "United States" AND year = end_year
                GROUP BY sector)
        SELECT CurrentYearExportData.sector, CurrentYearExports, NextYearExports, NextYearExports-CurrentYearExports AS NominalGrowth
        FROM CurrentYearExportData, NextYearExportData
        WHERE CurrentYearExportData.sector = NextYearExportData.sector
        ORDER BY NominalGrowth DESC
        LIMIT 5;
END; //

DELIMITER ;

DELIMITER //

DROP PROCEDURE IF EXISTS ShowUSBottomExportGrowths //

CREATE PROCEDURE ShowUSBottomExportGrowths(IN start_year INT, IN end_year INT)
BEGIN
   WITH CurrentYearExportData AS 
                (SELECT sector, SUM(export_value) AS CurrentYearExports
                FROM TradeFlows
                WHERE country_origin_name = "United States" AND year = start_year
                GROUP BY sector),
             NextYearExportData AS 
                (SELECT sector, SUM(export_value) AS NextYearExports
                FROM TradeFlows
                WHERE country_origin_name = "United States" AND year = end_year
                GROUP BY sector)
        SELECT CurrentYearExportData.sector, CurrentYearExports, NextYearExports, NextYearExports-CurrentYearExports AS NominalGrowth
        FROM CurrentYearExportData, NextYearExportData
        WHERE CurrentYearExportData.sector = NextYearExportData.sector
        ORDER BY NominalGrowth ASC
        LIMIT 5;
END; //

DELIMITER ;
