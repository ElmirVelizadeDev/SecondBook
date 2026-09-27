<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [

            /*
            |--------------------------------------------------------------------------
            | BUYING
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Buying',
                'question' => 'How can I buy a book?',
                'answer' => 'Browse the available books on SecondBook, open the book you want, review its details and condition, add it to your cart, proceed to checkout, enter your shipping information, select an available payment method, and confirm your order.',
                'sort_order' => 1,
            ],

            [
                'category' => 'Buying',
                'question' => 'How can I search for a book?',
                'answer' => 'Use the search field on the Books page to search for books by relevant information such as title, author, or other available book details. You can then review the matching results and open a book to see its full information.',
                'sort_order' => 2,
            ],

            [
                'category' => 'Buying',
                'question' => 'Can I filter books?',
                'answer' => 'Yes. The Books page provides filtering options that help you narrow the available books according to the available search and condition filters.',
                'sort_order' => 3,
            ],

            [
                'category' => 'Buying',
                'question' => 'Can I sort books?',
                'answer' => 'Yes. You can use the available sorting option on the Books page to organize the displayed book results according to the available sorting choices.',
                'sort_order' => 4,
            ],

            [
                'category' => 'Buying',
                'question' => 'Can I add a book to my wishlist?',
                'answer' => 'Yes. Available books can be added to your wishlist so you can keep track of books you may want to purchase later.',
                'sort_order' => 5,
            ],

            [
                'category' => 'Buying',
                'question' => 'Can I add books to my cart without leaving the page?',
                'answer' => 'Yes. SecondBook supports adding books to the cart without a full page refresh. After a successful addition, the cart count in the header can be updated automatically.',
                'sort_order' => 6,
            ],

            [
                'category' => 'Buying',
                'question' => 'Where can I see my cart?',
                'answer' => 'You can open the Cart page from the website navigation and review the books currently added to your cart before continuing to checkout.',
                'sort_order' => 7,
            ],

            [
                'category' => 'Buying',
                'question' => 'Can I remove a book from my cart?',
                'answer' => 'Yes. You can remove individual books from your cart using the available cart controls.',
                'sort_order' => 8,
            ],

            [
                'category' => 'Buying',
                'question' => 'Can I update the quantity of a book in my cart?',
                'answer' => 'Yes. If the available stock allows it, you can update the quantity of a book from the cart before completing checkout.',
                'sort_order' => 9,
            ],

            /*
            |--------------------------------------------------------------------------
            | BOOKS
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Books',
                'question' => 'What information is available on a book page?',
                'answer' => 'A book page can provide information such as the title, author, publisher, category, description, publication year, page count, language, price, stock, condition, and seller information when applicable.',
                'sort_order' => 10,
            ],

            [
                'category' => 'Books',
                'question' => 'What book conditions are available?',
                'answer' => 'SecondBook supports book conditions including New, Like New, Good, and Fair. The condition is used to describe the physical state of a book listed on the marketplace.',
                'sort_order' => 11,
            ],

            [
                'category' => 'Books',
                'question' => 'Can I see whether a book is available before ordering?',
                'answer' => 'Yes. The book information includes its available stock status. You should review the current availability before adding a book to your cart.',
                'sort_order' => 12,
            ],

            [
                'category' => 'Books',
                'question' => 'Can sellers list used books?',
                'answer' => 'Yes. Sellers can list second-hand books and specify the appropriate condition of each book before submitting the listing for marketplace approval.',
                'sort_order' => 13,
            ],

            [
                'category' => 'Books',
                'question' => 'Why is the condition of a book important?',
                'answer' => 'The condition helps buyers understand the expected physical state of a book before purchasing it. Buyers should review the listed condition and description carefully before placing an order.',
                'sort_order' => 14,
            ],

            [
                'category' => 'Books',
                'question' => 'Can I view books by category?',
                'answer' => 'Yes. SecondBook provides category browsing so you can explore books grouped according to their available categories.',
                'sort_order' => 15,
            ],

            [
                'category' => 'Books',
                'question' => 'Can I browse books by author?',
                'answer' => 'Yes. SecondBook provides an Authors page where available authors can be explored and their associated books can be discovered.',
                'sort_order' => 16,
            ],

            /*
            |--------------------------------------------------------------------------
            | ORDERS
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Orders',
                'question' => 'How can I place an order?',
                'answer' => 'Add the books you want to your cart, review your cart, proceed to checkout, enter your shipping information, select a payment method, and confirm the order.',
                'sort_order' => 17,
            ],

            [
                'category' => 'Orders',
                'question' => 'Where can I see my orders?',
                'answer' => 'You can view your previous and current orders from the Orders section of your account after signing in.',
                'sort_order' => 18,
            ],

            [
                'category' => 'Orders',
                'question' => 'How can I view an order?',
                'answer' => 'Open the Orders section of your account and select the relevant order to view its available details.',
                'sort_order' => 19,
            ],

            [
                'category' => 'Orders',
                'question' => 'Can I track my order?',
                'answer' => 'Yes. Orders can be followed through the available order tracking page when tracking information is available for the order.',
                'sort_order' => 20,
            ],

            [
                'category' => 'Orders',
                'question' => 'How can I check my order status?',
                'answer' => 'Open your Orders section and select the relevant order. The order details and available tracking information can be used to understand the current status of your order.',
                'sort_order' => 21,
            ],

            [
                'category' => 'Orders',
                'question' => 'Can I cancel an order?',
                'answer' => 'An order may be cancelled through the available cancellation option when the order is still eligible for cancellation. If the cancellation option is not available, the order may already have progressed beyond the applicable stage.',
                'sort_order' => 22,
            ],

            [
                'category' => 'Orders',
                'question' => 'What should I do if my order is late?',
                'answer' => 'First check the order status and available tracking information. If the order appears to be delayed beyond the expected delivery period, contact SecondBook support and provide your order details so the issue can be reviewed.',
                'sort_order' => 23,
            ],

            [
                'category' => 'Orders',
                'question' => 'What should I do if I received the wrong book?',
                'answer' => 'Contact SecondBook support as soon as possible and provide the order details. Explain that the delivered book does not match the book you ordered so the issue can be reviewed and the appropriate next step can be determined.',
                'sort_order' => 24,
            ],

            [
                'category' => 'Orders',
                'question' => 'What should I do if my order arrives damaged?',
                'answer' => 'Contact support and explain the condition of the order. If the issue concerns physical damage to the book, providing clear photos can help the support team review the situation.',
                'sort_order' => 25,
            ],

            /*
            |--------------------------------------------------------------------------
            | SHIPPING
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Shipping',
                'question' => 'How long does delivery take?',
                'answer' => 'The standard estimated delivery time is 3-5 business days. Actual delivery time can vary depending on the destination and shipping circumstances.',
                'sort_order' => 26,
            ],

            [
                'category' => 'Shipping',
                'question' => 'Do you offer international shipping?',
                'answer' => 'International shipping is available for selected destinations. Availability can depend on the destination and the shipping options supported for the order.',
                'sort_order' => 27,
            ],

            [
                'category' => 'Shipping',
                'question' => 'What shipping information is required?',
                'answer' => 'During checkout you should provide the required shipping information, including the country and other delivery details requested by the checkout form.',
                'sort_order' => 28,
            ],

            [
                'category' => 'Shipping',
                'question' => 'Where can I find shipping information?',
                'answer' => 'You can review the Shipping Information page for general delivery information and use your order details to check the status of a specific order.',
                'sort_order' => 29,
            ],

            [
                'category' => 'Shipping',
                'question' => 'What is the estimated delivery time?',
                'answer' => 'The standard estimated delivery message is 3-5 business days. This is an estimate and the actual delivery time may vary depending on shipping circumstances.',
                'sort_order' => 30,
            ],

            [
                'category' => 'Shipping',
                'question' => 'What should I do if my package is delayed?',
                'answer' => 'Check your order status and tracking information first. If the package remains delayed, contact SecondBook support with your order information so the situation can be reviewed.',
                'sort_order' => 31,
            ],

            /*
            |--------------------------------------------------------------------------
            | PAYMENTS
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Payments',
                'question' => 'Which payment methods are supported?',
                'answer' => 'SecondBook supports Cash on Delivery, Credit Card, Debit Card, and PayPal as available payment methods.',
                'sort_order' => 32,
            ],

            [
                'category' => 'Payments',
                'question' => 'When do I select my payment method?',
                'answer' => 'You select the available payment method during checkout before confirming your order.',
                'sort_order' => 33,
            ],

            [
                'category' => 'Payments',
                'question' => 'Can I pay by credit card?',
                'answer' => 'Yes. Credit Card is one of the supported payment methods on SecondBook when it is available for the order.',
                'sort_order' => 34,
            ],

            [
                'category' => 'Payments',
                'question' => 'Can I pay by debit card?',
                'answer' => 'Yes. Debit Card is one of the supported payment methods on SecondBook when it is available for the order.',
                'sort_order' => 35,
            ],

            [
                'category' => 'Payments',
                'question' => 'Can I pay with PayPal?',
                'answer' => 'Yes. PayPal is one of the supported payment methods on SecondBook when it is available for the order.',
                'sort_order' => 36,
            ],

            [
                'category' => 'Payments',
                'question' => 'Can I pay when my order is delivered?',
                'answer' => 'Yes. Cash on Delivery is supported as a payment method when it is available for the order.',
                'sort_order' => 37,
            ],

            [
                'category' => 'Payments',
                'question' => 'What happens if a payment needs to be reviewed?',
                'answer' => 'Payment information and status are associated with the order. If a payment requires attention, review the order details and contact SecondBook support if further assistance is needed.',
                'sort_order' => 38,
            ],

            /*
            |--------------------------------------------------------------------------
            | RETURNS & REFUNDS
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Returns',
                'question' => 'Can I request a refund?',
                'answer' => 'A refund request can be submitted when an order qualifies under the applicable return and refund requirements. Contact SecondBook support and provide your order information and the reason for the request.',
                'sort_order' => 39,
            ],

            [
                'category' => 'Returns',
                'question' => 'How can I request a return?',
                'answer' => 'Contact the SecondBook support team and explain what went wrong with your order. Provide the relevant order details and, when useful, photos showing the condition or problem with the book.',
                'sort_order' => 40,
            ],

            [
                'category' => 'Returns',
                'question' => 'What happens after I request a return?',
                'answer' => 'The return request is reviewed using the order information and the reason you provided. If the return is approved, you will receive instructions for returning the book. After the return is completed and approved, the applicable refund can be processed.',
                'sort_order' => 41,
            ],

            [
                'category' => 'Returns',
                'question' => 'When can a book be returned?',
                'answer' => 'A return may qualify when the book is significantly different from its listing, has undisclosed damage, is the wrong book, or the order has another serious issue. Each request is reviewed according to the applicable return requirements.',
                'sort_order' => 42,
            ],

            [
                'category' => 'Returns',
                'question' => 'Can I return a book if I changed my mind?',
                'answer' => 'A change of mind after receiving the book may not qualify for a return. Return requests are reviewed according to the applicable requirements and the condition of the order.',
                'sort_order' => 43,
            ],

            [
                'category' => 'Returns',
                'question' => 'Can I return a book with normal second-hand signs of use?',
                'answer' => 'Minor signs of normal second-hand use may not qualify for a return when those signs were already described in the listing. Buyers should review the listed condition and description before purchasing.',
                'sort_order' => 44,
            ],

            [
                'category' => 'Returns',
                'question' => 'Can I return a book that matches its listed condition?',
                'answer' => 'A book that matches the condition and description provided in its listing may not qualify for a return based only on dissatisfaction with normal second-hand characteristics.',
                'sort_order' => 45,
            ],

            [
                'category' => 'Returns',
                'question' => 'What if the book is significantly different from the listing?',
                'answer' => 'Contact SecondBook support and explain how the received book differs from the listing. Provide the order information and supporting photos when useful so the return request can be reviewed.',
                'sort_order' => 46,
            ],

            [
                'category' => 'Returns',
                'question' => 'What if the book has undisclosed damage?',
                'answer' => 'Contact support and describe the damage that was not disclosed in the listing. Clear photos can help the support team understand the condition and review the return request.',
                'sort_order' => 47,
            ],

            [
                'category' => 'Returns',
                'question' => 'Should I provide photos for a return request?',
                'answer' => 'Yes, when the issue concerns the physical condition of a book, clear photos can help the support team understand the problem and review the return request more effectively.',
                'sort_order' => 48,
            ],

            [
                'category' => 'Returns',
                'question' => 'Who reviews my return request?',
                'answer' => 'The SecondBook support team reviews the available order information, the reason for the return, and any relevant information provided with the request.',
                'sort_order' => 49,
            ],

            [
                'category' => 'Returns',
                'question' => 'How long does a refund take?',
                'answer' => 'Refund processing time can vary depending on the payment method and the status of the return review. A refund can be processed after the applicable return has been completed and approved.',
                'sort_order' => 50,
            ],

            [
                'category' => 'Returns',
                'question' => 'What should I keep when requesting a return?',
                'answer' => 'Keep your order information available and provide clear details about the issue. If the problem concerns the physical condition of the book, keep useful photos available as supporting information.',
                'sort_order' => 51,
            ],

            [
                'category' => 'Returns',
                'question' => 'What happens after a return is approved?',
                'answer' => 'After approval, you will receive instructions for returning the book. Follow those instructions and, once the return is completed and approved, the applicable refund will be processed.',
                'sort_order' => 52,
            ],

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Account',
                'question' => 'How can I create an account?',
                'answer' => 'Open the Register page, provide the required account information, create your password according to the displayed requirements, and complete the registration process.',
                'sort_order' => 53,
            ],

            [
                'category' => 'Account',
                'question' => 'How can I sign in?',
                'answer' => 'Open the Login page and enter the email address and password associated with your SecondBook account.',
                'sort_order' => 54,
            ],

            [
                'category' => 'Account',
                'question' => 'Can I use Remember Me when signing in?',
                'answer' => 'Yes. The Login page provides a Remember Me option that can keep your login session available according to the browser and authentication settings.',
                'sort_order' => 55,
            ],

            [
                'category' => 'Account',
                'question' => 'How can I change my account information?',
                'answer' => 'You can review and update available account information through the Account Settings area after signing in.',
                'sort_order' => 56,
            ],

            [
                'category' => 'Account',
                'question' => 'Can I update my profile information?',
                'answer' => 'Yes. Available profile information can be reviewed and updated through the account settings and profile functionality provided by SecondBook.',
                'sort_order' => 57,
            ],

            [
                'category' => 'Account',
                'question' => 'How can I change my password?',
                'answer' => 'Sign in to your account, open Account Settings, and use the password change section. Your new password must satisfy the password requirements and the confirmation password must match.',
                'sort_order' => 58,
            ],

            [
                'category' => 'Account',
                'question' => 'What are the password requirements?',
                'answer' => 'Your password must contain at least 8 characters, at least one lowercase letter, and at least one number. The password confirmation must match the new password, and the new password must be different from your current password.',
                'sort_order' => 59,
            ],

            [
                'category' => 'Account',
                'question' => 'Can I reset my password if I forget it?',
                'answer' => 'Yes. Use the Forgot Password flow, provide the email address associated with your account, receive the verification code, enter the valid code, and create a new password that meets the password requirements.',
                'sort_order' => 60,
            ],

            [
                'category' => 'Account',
                'question' => 'What is the verification code used for?',
                'answer' => 'The verification code is used to confirm your identity during the password reset process before you are allowed to create a new password.',
                'sort_order' => 61,
            ],

            [
                'category' => 'Account',
                'question' => 'How long is the password reset code valid?',
                'answer' => 'The password reset verification code is valid for a limited period. If the code expires, you should request a new verification code and continue the reset process with the new code.',
                'sort_order' => 62,
            ],

            [
                'category' => 'Account',
                'question' => 'What happens if my verification code expires?',
                'answer' => 'An expired verification code can no longer be used to reset the password. Request a new code and use the newly issued code within its valid period.',
                'sort_order' => 63,
            ],

            [
                'category' => 'Account',
                'question' => 'Can I request another verification code?',
                'answer' => 'Yes. The password reset process provides a resend option subject to the applicable resend cooldown. Wait for the cooldown when necessary before requesting another code.',
                'sort_order' => 64,
            ],

            /*
            |--------------------------------------------------------------------------
            | SELLING
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Selling',
                'question' => 'How can I become a seller?',
                'answer' => 'A registered user can submit a seller application through the Become a Seller page. The application is reviewed, and an approved application can result in the user becoming a seller and receiving a store.',
                'sort_order' => 65,
            ],

            [
                'category' => 'Selling',
                'question' => 'Can every registered user immediately sell books?',
                'answer' => 'No. A normal user cannot directly sell books. The user must submit a seller application and receive approval before using the seller marketplace functionality.',
                'sort_order' => 66,
            ],

            [
                'category' => 'Selling',
                'question' => 'What happens after I submit a seller application?',
                'answer' => 'The application is submitted for review. An administrator can review the application and approve or reject it. If approved, the user can become a seller and use the seller panel.',
                'sort_order' => 67,
            ],

            [
                'category' => 'Selling',
                'question' => 'Can a seller add books to the marketplace?',
                'answer' => 'Yes. An approved seller can use the seller panel to add books, provide the required book information, specify the condition and price, and submit the listing for marketplace approval.',
                'sort_order' => 68,
            ],

            [
                'category' => 'Selling',
                'question' => 'Can sellers edit their books?',
                'answer' => 'Yes. Approved sellers can manage their own book listings through the seller panel and edit available book information when permitted.',
                'sort_order' => 69,
            ],

            [
                'category' => 'Selling',
                'question' => 'Can sellers delete their books?',
                'answer' => 'Yes. Sellers can manage their own listings through the seller panel and delete books when the available seller controls allow the action.',
                'sort_order' => 70,
            ],

            [
                'category' => 'Selling',
                'question' => 'What information should a seller provide when listing a book?',
                'answer' => 'A seller should provide the available book information required by the listing form, such as the title, category, author, publisher, description, publication year, pages, language, price, stock, condition, and other applicable details.',
                'sort_order' => 71,
            ],

            [
                'category' => 'Selling',
                'question' => 'Does a seller need to specify the condition of a book?',
                'answer' => 'Yes. Sellers should specify the appropriate condition of the book so buyers can understand its physical state before purchasing.',
                'sort_order' => 72,
            ],

            [
                'category' => 'Selling',
                'question' => 'Can sellers manage their store information?',
                'answer' => 'Yes. Approved sellers have access to Store and Store Settings functionality where available, allowing them to manage the information associated with their seller store.',
                'sort_order' => 73,
            ],

            [
                'category' => 'Selling',
                'question' => 'Can sellers see their orders?',
                'answer' => 'Yes. Sellers have access to a seller order management area where they can review orders associated with their books and manage the available order actions.',
                'sort_order' => 74,
            ],

            [
                'category' => 'Selling',
                'question' => 'Can sellers see their sales?',
                'answer' => 'Yes. The seller panel provides a Sales area where sellers can review available sales information related to their marketplace activity.',
                'sort_order' => 75,
            ],

            /*
            |--------------------------------------------------------------------------
            | REVIEWS
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Reviews',
                'question' => 'Can I review a book I purchased?',
                'answer' => 'Yes. Customers can submit reviews for books they have purchased when the review option is available for the relevant order.',
                'sort_order' => 76,
            ],

            [
                'category' => 'Reviews',
                'question' => 'Where can I submit a review?',
                'answer' => 'When an eligible order provides the review option, you can open the relevant order and use the available review action to submit your feedback.',
                'sort_order' => 77,
            ],

            [
                'category' => 'Reviews',
                'question' => 'Why can I only review certain books?',
                'answer' => 'The review functionality is connected to purchased books and eligible orders. The review option may therefore only appear when the relevant order and book meet the applicable requirements.',
                'sort_order' => 78,
            ],

            /*
            |--------------------------------------------------------------------------
            | SUPPORT
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Support',
                'question' => 'How can I contact SecondBook support?',
                'answer' => 'You can contact the SecondBook support team through the Contact page. Provide a clear subject and explain your question or issue so the team can review your request.',
                'sort_order' => 79,
            ],

            [
                'category' => 'Support',
                'question' => 'What information should I provide when contacting support?',
                'answer' => 'Provide enough information for the support team to understand your request. For order-related issues, include the relevant order details and clearly explain what happened.',
                'sort_order' => 80,
            ],

            [
                'category' => 'Support',
                'question' => 'Where can I find answers to common questions?',
                'answer' => 'The SecondBook Help Center and FAQ page provide answers to common questions about buying, selling, orders, payments, shipping, accounts, returns, reviews, and other marketplace topics.',
                'sort_order' => 81,
            ],

            [
                'category' => 'Support',
                'question' => 'What should I do if I cannot find an answer in the FAQ?',
                'answer' => 'If the FAQ does not answer your question, use the Contact page to reach the SecondBook support team and provide details about what you need help with.',
                'sort_order' => 82,
            ],

            [
                'category' => 'Support',
                'question' => 'Can I use the Help Center to search for an answer?',
                'answer' => 'Yes. Enter your question or a relevant keyword in the Help Center search field. The search will take you to the FAQ page with the entered term so matching questions and answers can be displayed.',
                'sort_order' => 83,
            ],

            /*
            |--------------------------------------------------------------------------
            | PRIVACY
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Privacy',
                'question' => 'What information may SecondBook collect?',
                'answer' => 'Depending on how you use SecondBook, information may include account information, order information, communication information, and technical or usage information needed to operate, secure, and improve the marketplace.',
                'sort_order' => 84,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Why does SecondBook use my information?',
                'answer' => 'Information may be used to provide marketplace services, create and manage accounts, process orders and transactions, support users, improve website functionality, maintain security, and help prevent misuse.',
                'sort_order' => 85,
            ],

            [
                'category' => 'Privacy',
                'question' => 'How is my account information used?',
                'answer' => 'Account information is used for purposes connected with managing your account and providing requested SecondBook services. This can include account access, communication, and marketplace functionality.',
                'sort_order' => 86,
            ],

            [
                'category' => 'Privacy',
                'question' => 'What order information may be collected?',
                'answer' => 'Order information can include information required to process purchases, payments, shipping, deliveries, returns, refunds, and other activities related to marketplace transactions.',
                'sort_order' => 87,
            ],

            [
                'category' => 'Privacy',
                'question' => 'What information is collected when I contact support?',
                'answer' => 'Information you provide when contacting support, submitting requests, sending messages, or communicating with SecondBook may be handled so the team can understand and respond to your request.',
                'sort_order' => 88,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Why does SecondBook collect usage information?',
                'answer' => 'Technical and usage information may be collected to help maintain website functionality, improve usability and performance, support security, and understand how the website is used.',
                'sort_order' => 89,
            ],

            [
                'category' => 'Privacy',
                'question' => 'How does SecondBook protect my information?',
                'answer' => 'SecondBook uses reasonable technical and organizational measures intended to protect information against unauthorized access, misuse, alteration, or loss. Access to information should be limited to purposes connected with providing services.',
                'sort_order' => 90,
            ],

            [
                'category' => 'Privacy',
                'question' => 'How is account security handled?',
                'answer' => 'Account information is handled using security measures designed to help protect access to your account and reduce the risk of unauthorized use.',
                'sort_order' => 91,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Does SecondBook use cookies?',
                'answer' => 'SecondBook may use cookies and similar technologies to remember preferences, support essential website functionality, maintain sessions, and understand how the website is used.',
                'sort_order' => 92,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Can I manage cookies?',
                'answer' => 'Depending on your browser and its settings, you may be able to manage or restrict cookies through your browser controls. Restricting cookies may affect some website functionality.',
                'sort_order' => 93,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Can I access information associated with my account?',
                'answer' => 'Depending on applicable requirements, you may be able to request information about personal data associated with your account. Contact SecondBook if you have a question about your available privacy choices.',
                'sort_order' => 94,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Can I update my personal information?',
                'answer' => 'You can review and update certain account information through the available profile and account settings functionality. If you need assistance with information that cannot be changed there, contact SecondBook support.',
                'sort_order' => 95,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Can I request deletion of my personal information?',
                'answer' => 'Depending on applicable requirements, you may request deletion of certain personal information. Contact SecondBook support if you have a question about deletion or your available privacy choices.',
                'sort_order' => 96,
            ],

            [
                'category' => 'Privacy',
                'question' => 'How can I ask a question about privacy?',
                'answer' => 'If you have questions about the Privacy Policy or how your personal information is handled, contact the SecondBook support team through the Contact page.',
                'sort_order' => 97,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Can the Privacy Policy be updated?',
                'answer' => 'Yes. As SecondBook grows and its services change, the Privacy Policy may be updated from time to time. The updated version published on the Privacy Policy page should replace the previous version.',
                'sort_order' => 98,
            ],

            /*
            |--------------------------------------------------------------------------
            | GENERAL
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Account',
                'question' => 'Why do I need an account to use some features?',
                'answer' => 'An account allows SecondBook to associate marketplace activities such as orders, wishlist items, account settings, reviews, and seller functionality with the appropriate user.',
                'sort_order' => 99,
            ],

            [
                'category' => 'Orders',
                'question' => 'Where can I find information about my previous purchases?',
                'answer' => 'After signing in, open the Orders section of your account to review available information about your previous and current purchases.',
                'sort_order' => 100,
            ],

            [
                'category' => 'Account',
                'question' => 'What should I do if I cannot access my account?',
                'answer' => 'First make sure you are using the correct email address and password. If you have forgotten your password, use the password reset process. If the problem continues, contact SecondBook support.',
                'sort_order' => 101,
            ],

            [
                'category' => 'Orders',
                'question' => 'What should I do if I have a problem with my purchase?',
                'answer' => 'Review your order details first and identify the issue. For problems involving delivery, the book condition, the wrong item, payment, or a potential return, contact SecondBook support with the relevant order information.',
                'sort_order' => 102,
            ],

            [
                'category' => 'Returns',
                'question' => 'Where can I read the full Return Policy?',
                'answer' => 'You can visit the Return Policy page on SecondBook for information about the return process, eligibility, refund processing, and what to do when an order has a problem.',
                'sort_order' => 103,
            ],

            [
                'category' => 'Privacy',
                'question' => 'Where can I read the full Privacy Policy?',
                'answer' => 'You can visit the Privacy Policy page on SecondBook for information about collected information, its use, data protection, cookies, privacy choices, and policy updates.',
                'sort_order' => 104,
            ],
        ];

        foreach ($faqs as $faq) {
            DB::table('faqs')->updateOrInsert(
                [
                    'question' => $faq['question'],
                ],
                array_merge($faq, [
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}

