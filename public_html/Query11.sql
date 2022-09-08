-- ShowBidHistory.sql

DELIMITER //

DROP PROCEDURE IF EXISTS TopBilateralPartnerSector //

CREATE PROCEDURE TopBilateralPartnerSector(IN country VARCHAR(25), IN Year SMALLINT)
BEGIN
   IF EXISTS(SELECT T1.country_dest_name, T1.sector, T1.year, (T1.export_value + T2.export_value) AS TotalBilateralTrade
               FROM TradeFlows AS T1, TradeFlows AS T2
               WHERE T1.country_origin_name = country AND T1.year = Year AND T2.year = Year AND T1.country_origin_name = T2.country_dest_name
               AND T1.country_dest_name = T2.country_origin_name AND T1.sector = T2.sector
               ORDER BY TotalBilateralTrade DESC LIMIT 10) THEN
      SELECT T1.country_dest_name, T1.sector, T1.year, (T1.export_value + T2.export_value) AS TotalBilateralTrade
      FROM TradeFlows AS T1, TradeFlows AS T2
      WHERE T1.country_origin_name = country AND T1.year = Year AND T2.year = Year AND T1.country_origin_name = T2.country_dest_name
      AND T1.country_dest_name = T2.country_origin_name AND T1.sector = T2.sector
      ORDER BY TotalBilateralTrade DESC LIMIT 10;
   ELSE
      SELECT 'ERROR: Country not in database' AS country_dest_name;
   END IF;
END; //

DELIMITER ;
