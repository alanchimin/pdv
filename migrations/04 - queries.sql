USE pdv;

SELECT * FROM units ORDER BY unit_id DESC;
SELECT * FROM categories ORDER BY category_id DESC;
SELECT * FROM products ORDER BY product_id DESC;
SELECT * FROM payment_methods ORDER BY payment_method_id DESC;
SELECT * FROM users ORDER BY user_id DESC;
SELECT * FROM orders ORDER BY order_id DESC;
SELECT * FROM items ORDER BY item_id DESC;
SELECT * FROM screens ORDER BY screen_id DESC;
SELECT * FROM user_screens ORDER BY user_id DESC, screen_id DESC;
