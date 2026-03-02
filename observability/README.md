# Telescope / Debug Monitor Setup

1. Siapkan schema observability di database `dbknitt` dengan menjalankan:
   `observability/setup_sqlserver_observability.sql`
2. Jika nama DB/schema/table berbeda, sesuaikan:
   `observability/debug_config.php` pada key:
- `monitor_database`
- `monitor_schema`
- `monitor_table`
3. Akses dashboard monitor di:
   `/telescope`

Catatan:
- Jika DB monitor belum tersedia, logging otomatis fallback ke file:
  `temp/debug-monitor.log`
