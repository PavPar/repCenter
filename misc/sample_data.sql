INSERT INTO client (id, first_name, middle_name, last_name, phone, email) VALUES 
(1, 'Pavel', 'Evgenievuch', 'Tarasov', '552-1-21', 'tarasov@fake.email'),
(2, 'Ivan', 'Denisovich', 'Ivanonov', '430-123-1-5', 'iivanov@email.fake'),
(3, 'Daniil', 'Ruslanovich', 'Dinisov', '9021-123-112-21', 'ruslanddun@email.fake'),
(4, 'Valery', 'Denisovich', 'Utkin', '430-125-45', 'utkaval@email.fake'),
(5, 'Eva', 'Tarasova', 'Bronislava', '450-123-12', 'broneva@email.fake');

INSERT INTO worker (id, first_name, middle_name, last_name) VALUES
(1, 'Yury', 'Matveev', 'Varfolomey'),
(2, 'Vitali', 'Filippovich', 'Petrov'),
(3, 'Taras', 'Baramonov', 'Kovalchuk'),
(4, ' Alexsandr', 'Alexandrovich', 'Kovalev'),
(5, ' Sasha', 'Vladislavovich', 'Naoumov');

INSERT INTO `order` (id, worker_id, client_id, device, order_type_id, ordered_at, order_status, price) VALUES
(1, 1, 1, 'PC', 1, '2020-12-15 15:08:13', 0, 1500),
(2, 5, 2, 'Phone', 4, '2020-12-15 15:33:18', 0, 40000),
(3, 3, 1, 'Notebook', 3, '2020-12-15 15:34:46', 0, 25000),
(4, 2, 3, 'ipad', 6, '2020-12-15 15:36:51', 0, 500),
(5, 4, 4, 'PC', 2, '2020-12-15 15:47:49', 0, 24000),
(6, 4, 5, 'MacAir', 3, '2020-12-15 16:00:41', 0, 100000),
(7, 2, 4, 'vacuum cleaner', 2, '2020-12-15 16:08:05', 0, 15000),
(8, 1, 3, 'Keyboard', 1, '2020-12-15 16:09:00', 0, 2500),
(9, 1, 3, 'Keyboard', 4, '2020-12-15 16:09:45', 0, 4500),
(10, 1, 5, 'Iphone 15x', 5, '2020-12-15 16:11:04', 0, 13000);

INSERT INTO comment_order (order_id, created_at, comment) VALUES
(1, '2020-12-15 15:16:37', 'broken fan and powersource'),
(2, '2020-12-15 15:33:47', 'full repair due to water damage'),
(3, '2020-12-15 15:34:56', 'Broken parts replacement'),
(4, '2020-12-15 15:35:38', 'Client asked to help install app teams'),
(5, '2020-12-15 15:58:28', 'Computer looked like an ashtray'),
(6, '2020-12-15 16:00:48', 'Screen replacement'),
(7, '2020-12-15 16:08:25', 'Broken bag in cleaner'),
(8, '2020-12-15 16:09:12', 'Broken buttons'),
(9, '2020-12-15 16:10:04', 'buttons replacement'),
(10, '2020-12-15 16:11:11', 'Smartphone is in normal condition');
