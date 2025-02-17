DROP FUNCTION IF EXISTS distance;
DELIMITER $$
CREATE FUNCTION distance (Alat float, Alng float, Blat float, Blng float)
RETURNS float
DETERMINISTIC
BEGIN
	DECLARE dist float;
	SET dist = 6371 * acos(
		cos(radians(Blat))
		* cos(radians(Alat))
		* cos(radians(Alng) - radians(Blng))
		+ sin(radians(Blat))
		* sin(radians(Alat))
	);
	RETURN dist;
END$$
DELIMITER ;
