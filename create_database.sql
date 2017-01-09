CREATE TABLE tb_time_entry(
    time_entry SERIAL PRIMARY KEY,
    barcode_scanner_id INTEGER NOT NULL,
    unit VARCHAR(20),
    employee_name VARCHAR(128),
    station VARCHAR(128),
    start_time TIMESTAMP,
    end_time TIMESTAMP
);
