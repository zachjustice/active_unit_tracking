-- TEST DATA
BEGIN;

INSERT INTO tb_department(name) VALUES('Department 1');

INSERT INTO
    tb_station_type(name, department)
VALUES(
    'Station Type 1',
    (SELECT department FROM tb_department WHERE name = 'Department 1')
);

INSERT INTO
    tb_station(name, station_type)
VALUES(
    'Station 1',
    (SELECT station_type FROM tb_station_type WHERE name = 'Station Type 1')
);

INSERT INTO
    tb_model(name)
VALUES(
    'MP400'
);

INSERT INTO 
    tb_unit(label, model)
VALUES(
    'RGLMP400MAR2016A',
    (SELECT model FROM tb_model LIMIT 1)
);

INSERT INTO 
    tb_entity(name, email_address, password)
VALUES(
    'Lily Li',
    'lilyli@gmail.com',
    crypt('password', gen_salt('bf', 8))
);

INSERT INTO 
    tb_entity(name, email_address, password)
VALUES(
    'Zach Justice',
    'zachjustice123@gmail.com',
    crypt('password', gen_salt('bf', 8))
);

COMMIT;

