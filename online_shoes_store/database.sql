-- Database: online_shoes
CREATE DATABASE IF NOT EXISTS online_shoes DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE online_shoes;

-- users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- admins table
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- products
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT,
  image VARCHAR(500) DEFAULT 'assets/sample-shoe.png',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- orders
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  address TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- order_items
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  qty INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- Sample data
INSERT INTO products (name,price,description,image) VALUES
('Sporty Runner','2499.00','Comfortable running shoe for daily training.','assets/sample-shoe.png'),
('Casual Sneaker','1999.00','Stylish sneaker for everyday casual wear.','assets/sample-shoe.png'),
('Leather Formal','3499.00','Classic leather shoe perfect for formal events.','assets/sample-shoe.png');

-- Admin account (email: admin@store.com, password: admin123)
INSERT INTO admins (name,email,password) VALUES
('Store Admin','admin@store.com', 'REPLACE_WITH_HASH');

-- Note: Replace REPLACE_WITH_HASH with PHP password_hash('admin123', PASSWORD_DEFAULT) output


INSERT INTO products (name, price, description, image) VALUES
('Nike sneakers', '2599.00', 'Lightweight running shoes with superior cushioning for daily workouts.', 'assets/1.jpeg'),
('sports nike ', '1899.00', 'Trendy sneakers designed for casual streetwear style.', 'assets/2.jpeg'),
('Classic shoes', '3799.00', 'Elegant formal shoes made from premium leather.', 'assets/3.jpeg'),
('Addidas sports shoes', '2999.00', 'Durable trail shoes for hiking and outdoor adventures.', 'assets/4.jpeg'),
('classic addidas', '1799.00', 'Soft and comfortable shoes perfect for long walks.', 'assets/5.jpeg'),
('sports shoes', '3299.00', 'High-performance sports shoes built for maximum speed.', 'assets/6.jpeg'),
('running shoes', '1599.00', 'Retro-style canvas sneakers for everyday comfort.', 'assets/7.jpeg'),
('addidas sports shoes', '4099.00', 'Elegant office shoes crafted for business professionals.', 'assets/8.jpeg'),
('redtap casual shoes', '2799.00', 'Sturdy gym shoes ideal for heavy lifting and training.', 'assets/9.jpeg'),
('white sneakers', '2399.00', 'Stylish and breathable running shoes for all terrains.', 'assets/10.jpeg'),
('brown casual', '1699.00', 'Slip-on shoes with memory foam soles for ultimate comfort.', 'assets/11.jpeg'),
('white-green casual', '3199.00', 'Rugged shoes built for trekking and rough trails.', 'assets/12.jpeg'),
('Campus sports shoes', '1499.00', 'Light sandals with airy design for summer comfort.', 'assets/13.jpeg'),
('red sneakers', '2899.00', 'Glow-in-the-dark sports shoes for evening jogs.', 'assets/14.jpeg'),
('casual sports wear', '3599.00', 'Stylish loafers with soft lining for premium comfort.', 'assets/15.jpeg'),
('Sparx blue shoes', '2699.00', 'Dynamic racing shoes designed for speed and performance.', 'assets/16.jpeg'),
('Sparx sports shoes', '2299.00', 'Suede desert boots offering style and durability.', 'assets/17.jpeg'),
('Red puma sneakers', '1899.00', 'Super soft sneakers that feel like walking on clouds.', 'assets/18.jpeg'),
('puma sneakers', '3099.00', 'Performance trainers with enhanced breathability.', 'assets/19.jpeg'),
('puma casual shoes', '3399.00', 'High-traction running shoes with anti-slip soles.', 'assets/20.jpeg')
('Elegant Heels','2999.00','Stylish high heels perfect for parties and formal events.','assets/22.jpg'),
('Classic Ballet Flats','1599.00','Comfortable ballet flats for everyday wear.','assets/23.jpg'),
('Women Sport Runner','2499.00','Lightweight running shoes designed for women athletes.','assets/24.jpg'),
('Casual Canvas Sneaker','1899.00','Trendy canvas sneakers for casual outings.','assets/25.jpg'),
('Stylish Wedge Sandal','2199.00','Fashionable wedge sandals with comfortable sole.','assetse26.jpg'),
('Leather Office Pumps','3299.00','Elegant leather pumps ideal for office wear.','assets/27.jpg'),
('Summer Flip Flops','999.00','Soft and comfortable flip flops for summer days.','assets/28.png'),
('Party Glitter Heels','3499.00','Sparkling glitter heels perfect for party nights.','assets/29.jpg'),
('Elegant Strap Sandals','1999.00','Chic strap sandals suitable for casual and party wear.','assets/31.jpg'),
('Fashion Platform Sneaker','2799.00','Modern platform sneakers for stylish women.','assets/32.jpg'),
('Comfort Walking Shoes','2299.00','Soft cushioned walking shoes for daily comfort.','assets/33.jpg');
