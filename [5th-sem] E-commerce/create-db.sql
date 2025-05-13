create db ec-app;
use ec-app;

create table User (
	-- same as customer?
);

create table Customer (
	ID char(10) primary key,
	Name varchar(40) not null,
	Phone int(10) not null,
	Email varchar(40),
	Address varchar(40) not null,
	DoB date -- age-restricted products
);

create table Product (
	ID char(10) primary key,
	Price decimal(5, 2)
);

create table Order (
	ID char(10) primary key,
	Date date,
	Customer_ID char(10),
	Total decimal(8, 2),
	Status enum('Processing', 'Delivered', 'Cancelled'),
	Shipping_charge decimal(5, 2),
	VAT decimal(5, 2),
	foreign key(Customer_ID) references Customer(ID)
);

create table Order_details (
	Order_ID char(10),
	Product_ID char(10),
	Quantity int(5),
	Price decimal(5, 2),
	Subtotal decimal(6, 2),
	foreign key(Order_ID) references Order(ID),
	foreign key(Product_ID) references Product(ID),
	foreign key(Product_price) references Product(Price)
);

create table Product_review (
	ID char(10),
	Customer_ID char(10),
	Description text,
	Rating int(1),
	Product_ID char(10),
	foreign key(Customer_ID) references Customer(ID),
	foreign key(Product_ID) references Product(ID)
);

create table Country (
	Code char(2) primary key,
	Name char(20),
	Calling_code int(3)
);

create table Province (
	ID int(4) primary key auto_increment,
	Name char(40),
	Country_ID char(2),
	foreign key(Country_ID) references Country(ID)
);

create table District (
	ID int(4) primary key auto_increment,
	Name char(40),
	Province_ID char(40),
	foreign key(Province_ID) references Province(ID)
);

create table Municipality (
	ID int(6) primary key auto_increment,
	Name char(40),
	District_ID char(40),
	foreign key(District_ID) references District(ID)
);
