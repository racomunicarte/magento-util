--SET foreign_key_checks = 0;

TRUNCATE dataflow_batch_export ; 
TRUNCATE dataflow_batch_import ; 

TRUNCATE log_customer ; 
TRUNCATE log_quote ; 
TRUNCATE log_summary ; 
TRUNCATE log_summary_type ; 
TRUNCATE log_url ; 
TRUNCATE log_url_info ; 
TRUNCATE log_visitor ; 
TRUNCATE log_visitor_info ; 
TRUNCATE log_visitor_online ; 

TRUNCATE report_event ;
TRUNCATE report_compared_product_index;
TRUNCATE report_viewed_product_aggregated_daily;
TRUNCATE report_viewed_product_aggregated_monthly;
TRUNCATE report_viewed_product_aggregated_yearly;
TRUNCATE report_viewed_product_index;

TRUNCATE sales_bestsellers_aggregated_daily;
TRUNCATE sales_bestsellers_aggregated_monthly;
TRUNCATE sales_bestsellers_aggregated_yearly;
