# Concept
## Database design
- User
- Customer
- Product
- Order
- Order details
- Payment details
- Product review/rating
- Manufacturer
- Product category (Category ID, Category Name, Parent Category ID, Status)
- Municipality
- District
- Province
- Country
- Payment method (ID, Name, Status)

Product Category
ID | Product | Category ID
--- | --- | ---
1 | Mens Wear | -
2 | Ladies Wear | -
3 | Pant | 1
4 | Pant | 2
5 | Shirt | 1
6 | Half Shirt | 5

Category: Gents wear
- Subcategory: Pant
	- Product: Lewis Pant
	- Priduct ID
	- Description
	- Unit Price (MRP)
	- Quantity
	- Manufacturer
	- Photo URL

Customer:
- Customer ID
- Name
- Phone no.
- Email
- Address
- DoB

Customer Billing address
- Customer ID
- Street
- House no.
- Ward
- Municipality-id
- District-id
- Provice-id
- Country-id

Customer Shipping address
- Customer ID
- ...rest

Order
- Order ID
- Order date
- Customer ID
- Order total
- Order status
- Shipping charge
- VAT

Order details
- Order ID
- Product ID
- Quantity
- Price
- Subtotal

Order ID | Product ID | Quantity | Price | Subtotal
--- | --- | --- | --- | ---
O101 | P102 | 12 | 400 | 4800
O101 | P103 | 3 | 1200 | 3600
O101 | P101 | 2 | 100 | 200
| | Total | | | 9600

Product Review
- Review ID
- Customer ID
- Review description
- Rating
- Product ID

Country
- Country ID
- Country Name

Province
- Province ID
- Province Name
- Country ID

District
- ID
- Name
- Provice ID

Municipality
- ID
- Name
- District ID

## Cart
Cart is saved in session, not in database

## Frontend
### Dashboards
- Customer Order
- Admin Panel
- Customer Panel
