<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [

            // BUYING
            [
                'category' => 'Buying',
                'question' => 'How can I buy a book?',
                'answer' => 'Browse the available books on SecondBook, open the book you want, review its details and condition, add it to your cart, proceed to checkout, enter your shipping information, select an available payment method, and confirm your order.',
                'sort_order' => 1,
            ],
            [
                'category' => 'Buying',
                'question' => 'How can I search for a book?',
                'answer' => 'Use the search field on the Books page to search for books by title, author, or other available book information.',
                'sort_order' => 2,
            ],
            [
                'category' => 'Buying',
                'question' => 'Can I filter books?',
                'answer' => 'Yes. The Books page provides filtering options that help you narrow the available books according to the available filters.',
                'sort_order' => 3,
            ],
            [
                'category' => 'Buying',
                'question' => 'Can I sort books?',
                'answer' => 'Yes. You can use the available sorting options on the Books page to organize displayed book results.',
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
                'answer' => 'Yes. SecondBook supports adding books to the cart without a full page refresh.',
                'sort_order' => 6,
            ],
            [
                'category' => 'Buying',
                'question' => 'Where can I see my cart?',
                'answer' => 'You can open the Cart page from the website navigation and review the books currently added to your cart.',
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
                'answer' => 'Yes. If available stock allows it, you can update the quantity of a book from the cart.',
                'sort_order' => 9,
            ],

            // BOOKS
            [
                'category' => 'Books',
                'question' => 'What information is available on a book page?',
                'answer' => 'A book page can provide the title, author, publisher, category, description, publication year, page count, language, price, stock, condition, and seller information.',
                'sort_order' => 10,
            ],
            [
                'category' => 'Books',
                'question' => 'What book conditions are available?',
                'answer' => 'SecondBook supports New, Like New, Good, and Fair conditions.',
                'sort_order' => 11,
            ],
            [
                'category' => 'Books',
                'question' => 'Can I see whether a book is available before ordering?',
                'answer' => 'Yes. The book information includes its available stock status.',
                'sort_order' => 12,
            ],
            [
                'category' => 'Books',
                'question' => 'Can sellers list used books?',
                'answer' => 'Yes. Sellers can list second-hand books and specify the appropriate condition before submitting the listing for approval.',
                'sort_order' => 13,
            ],
            [
                'category' => 'Books',
                'question' => 'Why is the condition of a book important?',
                'answer' => 'The condition helps buyers understand the expected physical state of a book before purchasing it.',
                'sort_order' => 14,
            ],
            [
                'category' => 'Books',
                'question' => 'Can I view books by category?',
                'answer' => 'Yes. SecondBook provides category browsing so you can explore books by category.',
                'sort_order' => 15,
            ],
            [
                'category' => 'Books',
                'question' => 'Can I browse books by author?',
                'answer' => 'Yes. SecondBook provides an Authors page where available authors and their books can be explored.',
                'sort_order' => 16,
            ],

            // ORDERS
            [
                'category' => 'Orders',
                'question' => 'How can I place an order?',
                'answer' => 'Add the books you want to your cart, review your cart, proceed to checkout, enter your shipping information, select a payment method, and confirm the order.',
                'sort_order' => 17,
            ],
            [
                'category' => 'Orders',
                'question' => 'Where can I see my orders?',
                'answer' => 'You can view your previous and current orders from the Orders section of your account.',
                'sort_order' => 18,
            ],
            [
                'category' => 'Orders',
                'question' => 'How can I view an order?',
                'answer' => 'Open the Orders section and select the relevant order to view its details.',
                'sort_order' => 19,
            ],
            [
                'category' => 'Orders',
                'question' => 'Can I track my order?',
                'answer' => 'Yes. Orders can be followed through the available order tracking page when tracking information is available.',
                'sort_order' => 20,
            ],
            [
                'category' => 'Orders',
                'question' => 'How can I check my order status?',
                'answer' => 'Open your Orders section and select the relevant order to view its current status.',
                'sort_order' => 21,
            ],
            [
                'category' => 'Orders',
                'question' => 'Can I cancel an order?',
                'answer' => 'An order may be cancelled when it is still eligible for cancellation.',
                'sort_order' => 22,
            ],
            [
                'category' => 'Orders',
                'question' => 'What should I do if my order is late?',
                'answer' => 'Check the order status and tracking information first. If the order is delayed, contact SecondBook support with your order details.',
                'sort_order' => 23,
            ],
            [
                'category' => 'Orders',
                'question' => 'What should I do if I received the wrong book?',
                'answer' => 'Contact SecondBook support and provide your order details so the issue can be reviewed.',
                'sort_order' => 24,
            ],
            [
                'category' => 'Orders',
                'question' => 'What should I do if my order arrives damaged?',
                'answer' => 'Contact support and explain the condition of the order. Clear photos can help with the review.',
                'sort_order' => 25,
            ],

            // SHIPPING
            [
                'category' => 'Shipping',
                'question' => 'How long does delivery take?',
                'answer' => 'The standard estimated delivery time is 3-5 business days.',
                'sort_order' => 26,
            ],
            [
                'category' => 'Shipping',
                'question' => 'Do you offer international shipping?',
                'answer' => 'International shipping is available for selected destinations depending on supported shipping options.',
                'sort_order' => 27,
            ],
            [
                'category' => 'Shipping',
                'question' => 'What shipping information is required?',
                'answer' => 'During checkout you should provide the required shipping information requested by the checkout form.',
                'sort_order' => 28,
            ],
            [
                'category' => 'Shipping',
                'question' => 'Where can I find shipping information?',
                'answer' => 'You can review the Shipping Information page for general delivery information.',
                'sort_order' => 29,
            ],
            [
                'category' => 'Shipping',
                'question' => 'What is the estimated delivery time?',
                'answer' => 'The standard estimated delivery message is 3-5 business days.',
                'sort_order' => 30,
            ],
            [
                'category' => 'Shipping',
                'question' => 'What should I do if my package is delayed?',
                'answer' => 'Check your order status and tracking information first. If the package remains delayed, contact SecondBook support.',
                'sort_order' => 31,
            ],

            // PAYMENTS
            [
                'category' => 'Payments',
                'question' => 'Which payment methods are supported?',
                'answer' => 'SecondBook supports Cash on Delivery, Credit Card, Debit Card, and PayPal.',
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
                'answer' => 'Yes. Credit Card is a supported payment method when available.',
                'sort_order' => 34,
            ],
            [
                'category' => 'Payments',
                'question' => 'Can I pay by debit card?',
                'answer' => 'Yes. Debit Card is a supported payment method when available.',
                'sort_order' => 35,
            ],
            [
                'category' => 'Payments',
                'question' => 'Can I pay with PayPal?',
                'answer' => 'Yes. PayPal is a supported payment method when available.',
                'sort_order' => 36,
            ],
            [
                'category' => 'Payments',
                'question' => 'Can I pay when my order is delivered?',
                'answer' => 'Yes. Cash on Delivery is supported when available for the order.',
                'sort_order' => 37,
            ],
            [
                'category' => 'Payments',
                'question' => 'What happens if a payment needs to be reviewed?',
                'answer' => 'Review the order details and contact SecondBook support if further assistance is needed.',
                'sort_order' => 38,
            ],

            // RETURNS
            [
                'category' => 'Returns',
                'question' => 'Can I request a refund?',
                'answer' => 'A refund request can be submitted when an order qualifies under the applicable return and refund requirements.',
                'sort_order' => 39,
            ],
            [
                'category' => 'Returns',
                'question' => 'How can I request a return?',
                'answer' => 'Contact SecondBook support and provide the relevant order details and reason for the request.',
                'sort_order' => 40,
            ],
            [
                'category' => 'Returns',
                'question' => 'What happens after I request a return?',
                'answer' => 'The request is reviewed using the order information and reason provided. If approved, return instructions will be provided.',
                'sort_order' => 41,
            ],
            [
                'category' => 'Returns',
                'question' => 'When can a book be returned?',
                'answer' => 'A return may qualify when the book is significantly different from its listing, has undisclosed damage, or is the wrong book.',
                'sort_order' => 42,
            ],
            [
                'category' => 'Returns',
                'question' => 'Can I return a book if I changed my mind?',
                'answer' => 'A change of mind may not qualify for a return. Requests are reviewed according to the applicable requirements.',
                'sort_order' => 43,
            ],
            [
                'category' => 'Returns',
                'question' => 'Can I return a book with normal second-hand signs of use?',
                'answer' => 'Minor signs of normal second-hand use may not qualify when they were already described in the listing.',
                'sort_order' => 44,
            ],
            [
                'category' => 'Returns',
                'question' => 'Can I return a book that matches its listed condition?',
                'answer' => 'A book that matches the listed condition and description may not qualify for a return based only on normal second-hand characteristics.',
                'sort_order' => 45,
            ],
            [
                'category' => 'Returns',
                'question' => 'What if the book is significantly different from the listing?',
                'answer' => 'Contact SecondBook support and explain how the received book differs from the listing.',
                'sort_order' => 46,
            ],
            [
                'category' => 'Returns',
                'question' => 'What if the book has undisclosed damage?',
                'answer' => 'Contact support and describe the damage. Clear photos can help with the review.',
                'sort_order' => 47,
            ],
            [
                'category' => 'Returns',
                'question' => 'Should I provide photos for a return request?',
                'answer' => 'Yes. Clear photos can help the support team understand physical problems with a book.',
                'sort_order' => 48,
            ],
            [
                'category' => 'Returns',
                'question' => 'Who reviews my return request?',
                'answer' => 'The SecondBook support team reviews the order information and reason for the return.',
                'sort_order' => 49,
            ],
            [
                'category' => 'Returns',
                'question' => 'How long does a refund take?',
                'answer' => 'Refund processing time can vary depending on the payment method and return review.',
                'sort_order' => 50,
            ],
            [
                'category' => 'Returns',
                'question' => 'What should I keep when requesting a return?',
                'answer' => 'Keep your order information and useful photos available when requesting a return.',
                'sort_order' => 51,
            ],
            [
                'category' => 'Returns',
                'question' => 'What happens after a return is approved?',
                'answer' => 'You will receive return instructions. After the return is completed and approved, the applicable refund will be processed.',
                'sort_order' => 52,
            ],

            // ACCOUNT
            [
                'category' => 'Account',
                'question' => 'How can I create an account?',
                'answer' => 'Open the Register page, provide the required information, create your password, and complete registration.',
                'sort_order' => 53,
            ],
            [
                'category' => 'Account',
                'question' => 'How can I sign in?',
                'answer' => 'Open the Login page and enter the email address and password associated with your account.',
                'sort_order' => 54,
            ],
            [
                'category' => 'Account',
                'question' => 'Can I use Remember Me when signing in?',
                'answer' => 'Yes. The Login page provides a Remember Me option.',
                'sort_order' => 55,
            ],
            [
                'category' => 'Account',
                'question' => 'How can I change my account information?',
                'answer' => 'You can review and update available account information through Account Settings.',
                'sort_order' => 56,
            ],
            [
                'category' => 'Account',
                'question' => 'Can I update my profile information?',
                'answer' => 'Yes. Available profile information can be updated through account settings and profile functionality.',
                'sort_order' => 57,
            ],
            [
                'category' => 'Account',
                'question' => 'How can I change my password?',
                'answer' => 'Open Account Settings and use the password change section.',
                'sort_order' => 58,
            ],
            [
                'category' => 'Account',
                'question' => 'What are the password requirements?',
                'answer' => 'Your password must contain at least 8 characters, one lowercase letter, and one number.',
                'sort_order' => 59,
            ],
            [
                'category' => 'Account',
                'question' => 'Can I reset my password if I forget it?',
                'answer' => 'Yes. Use Forgot Password, receive the verification code, enter it, and create a new password.',
                'sort_order' => 60,
            ],
            [
                'category' => 'Account',
                'question' => 'What is the verification code used for?',
                'answer' => 'The verification code confirms your identity during the password reset process.',
                'sort_order' => 61,
            ],
            [
                'category' => 'Account',
                'question' => 'How long is the password reset code valid?',
                'answer' => 'The password reset verification code is valid for a limited period.',
                'sort_order' => 62,
            ],
            [
                'category' => 'Account',
                'question' => 'What happens if my verification code expires?',
                'answer' => 'An expired verification code can no longer be used. Request a new code.',
                'sort_order' => 63,
            ],
            [
                'category' => 'Account',
                'question' => 'Can I request another verification code?',
                'answer' => 'Yes. You can request another code subject to the applicable resend cooldown.',
                'sort_order' => 64,
            ],

            // SELLING
            [
                'category' => 'Selling',
                'question' => 'How can I become a seller?',
                'answer' => 'Submit a seller application through the Become a Seller page. Approved applications can result in seller access and a store.',
                'sort_order' => 65,
            ],
            [
                'category' => 'Selling',
                'question' => 'Can every registered user immediately sell books?',
                'answer' => 'No. A normal user must submit a seller application and receive approval.',
                'sort_order' => 66,
            ],
            [
                'category' => 'Selling',
                'question' => 'What happens after I submit a seller application?',
                'answer' => 'The application is reviewed by an administrator and can be approved or rejected.',
                'sort_order' => 67,
            ],
            [
                'category' => 'Selling',
                'question' => 'Can a seller add books to the marketplace?',
                'answer' => 'Yes. Approved sellers can add books through the seller panel and submit listings for approval.',
                'sort_order' => 68,
            ],
            [
                'category' => 'Selling',
                'question' => 'Can sellers edit their books?',
                'answer' => 'Yes. Sellers can manage and edit their own book listings through the seller panel.',
                'sort_order' => 69,
            ],
            [
                'category' => 'Selling',
                'question' => 'Can sellers delete their books?',
                'answer' => 'Yes. Sellers can manage their own listings and delete books when permitted.',
                'sort_order' => 70,
            ],
            [
                'category' => 'Selling',
                'question' => 'What information should a seller provide when listing a book?',
                'answer' => 'Sellers should provide the required title, category, author, publisher, description, year, pages, language, price, stock, condition, and other applicable information.',
                'sort_order' => 71,
            ],
            [
                'category' => 'Selling',
                'question' => 'Does a seller need to specify the condition of a book?',
                'answer' => 'Yes. Sellers should specify the appropriate condition so buyers understand the physical state.',
                'sort_order' => 72,
            ],
            [
                'category' => 'Selling',
                'question' => 'Can sellers manage their store information?',
                'answer' => 'Yes. Approved sellers can manage available store information through Store and Store Settings.',
                'sort_order' => 73,
            ],
            [
                'category' => 'Selling',
                'question' => 'Can sellers see their orders?',
                'answer' => 'Yes. Sellers can review orders associated with their books through the seller order management area.',
                'sort_order' => 74,
            ],
            [
                'category' => 'Selling',
                'question' => 'Can sellers see their sales?',
                'answer' => 'Yes. The seller panel provides a Sales area for marketplace sales information.',
                'sort_order' => 75,
            ],

            // REVIEWS
            [
                'category' => 'Reviews',
                'question' => 'Can I review a book I purchased?',
                'answer' => 'Yes. Customers can submit reviews for purchased books when the review option is available.',
                'sort_order' => 76,
            ],
            [
                'category' => 'Reviews',
                'question' => 'Where can I submit a review?',
                'answer' => 'Open the relevant eligible order and use the available review action.',
                'sort_order' => 77,
            ],
            [
                'category' => 'Reviews',
                'question' => 'Why can I only review certain books?',
                'answer' => 'Reviews are connected to purchased books and eligible orders, so the review option may only appear when requirements are met.',
                'sort_order' => 78,
            ],

            // SUPPORT
            [
                'category' => 'Support',
                'question' => 'How can I contact SecondBook support?',
                'answer' => 'You can contact SecondBook support through the Contact page.',
                'sort_order' => 79,
            ],
            [
                'category' => 'Support',
                'question' => 'What information should I provide when contacting support?',
                'answer' => 'Provide enough information to understand your request. For order issues, include the relevant order details.',
                'sort_order' => 80,
            ],
            [
                'category' => 'Support',
                'question' => 'Where can I find answers to common questions?',
                'answer' => 'The SecondBook Help Center and FAQ page provide answers to common marketplace questions.',
                'sort_order' => 81,
            ],
            [
                'category' => 'Support',
                'question' => 'What should I do if I cannot find an answer in the FAQ?',
                'answer' => 'Use the Contact page to reach the SecondBook support team.',
                'sort_order' => 82,
            ],
            [
                'category' => 'Support',
                'question' => 'Can I use the Help Center to search for an answer?',
                'answer' => 'Yes. Enter a question or keyword in the Help Center search field.',
                'sort_order' => 83,
            ],

            // PRIVACY
            [
                'category' => 'Privacy',
                'question' => 'What information may SecondBook collect?',
                'answer' => 'Information may include account, order, communication, technical, and usage information needed to operate the marketplace.',
                'sort_order' => 84,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Why does SecondBook use my information?',
                'answer' => 'Information may be used to provide services, process orders, support users, improve functionality, and maintain security.',
                'sort_order' => 85,
            ],
            [
                'category' => 'Privacy',
                'question' => 'How is my account information used?',
                'answer' => 'Account information is used to manage your account and provide requested SecondBook services.',
                'sort_order' => 86,
            ],
            [
                'category' => 'Privacy',
                'question' => 'What order information may be collected?',
                'answer' => 'Order information can include information needed for purchases, payments, shipping, returns, refunds, and marketplace transactions.',
                'sort_order' => 87,
            ],
            [
                'category' => 'Privacy',
                'question' => 'What information is collected when I contact support?',
                'answer' => 'Information provided through support requests and communications may be handled to understand and respond to your request.',
                'sort_order' => 88,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Why does SecondBook collect usage information?',
                'answer' => 'Usage information may help maintain functionality, improve usability and performance, and support security.',
                'sort_order' => 89,
            ],
            [
                'category' => 'Privacy',
                'question' => 'How does SecondBook protect my information?',
                'answer' => 'SecondBook uses reasonable technical and organizational measures intended to protect information.',
                'sort_order' => 90,
            ],
            [
                'category' => 'Privacy',
                'question' => 'How is account security handled?',
                'answer' => 'Account information is handled using security measures designed to reduce unauthorized access.',
                'sort_order' => 91,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Does SecondBook use cookies?',
                'answer' => 'SecondBook may use cookies and similar technologies for essential functionality, sessions, preferences, and usage analysis.',
                'sort_order' => 92,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Can I manage cookies?',
                'answer' => 'You can manage or restrict cookies through your browser settings.',
                'sort_order' => 93,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Can I access information associated with my account?',
                'answer' => 'Depending on applicable requirements, you may request information associated with your account.',
                'sort_order' => 94,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Can I update my personal information?',
                'answer' => 'You can update certain account information through profile and account settings.',
                'sort_order' => 95,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Can I request deletion of my personal information?',
                'answer' => 'Depending on applicable requirements, you may request deletion of certain personal information.',
                'sort_order' => 96,
            ],
            [
                'category' => 'Privacy',
                'question' => 'How can I ask a question about privacy?',
                'answer' => 'Contact the SecondBook support team through the Contact page.',
                'sort_order' => 97,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Can the Privacy Policy be updated?',
                'answer' => 'Yes. The Privacy Policy may be updated from time to time as SecondBook services change.',
                'sort_order' => 98,
            ],

            // GENERAL
            [
                'category' => 'Account',
                'question' => 'Why do I need an account to use some features?',
                'answer' => 'An account allows SecondBook to associate orders, wishlist items, settings, reviews, and seller functionality with the correct user.',
                'sort_order' => 99,
            ],
            [
                'category' => 'Orders',
                'question' => 'Where can I find information about my previous purchases?',
                'answer' => 'After signing in, open the Orders section of your account.',
                'sort_order' => 100,
            ],
            [
                'category' => 'Account',
                'question' => 'What should I do if I cannot access my account?',
                'answer' => 'Check your login details or use the password reset process. If the problem continues, contact support.',
                'sort_order' => 101,
            ],
            [
                'category' => 'Orders',
                'question' => 'What should I do if I have a problem with my purchase?',
                'answer' => 'Review your order details and contact SecondBook support with the relevant order information.',
                'sort_order' => 102,
            ],
            [
                'category' => 'Returns',
                'question' => 'Where can I read the full Return Policy?',
                'answer' => 'Visit the Return Policy page on SecondBook for detailed return and refund information.',
                'sort_order' => 103,
            ],
            [
                'category' => 'Privacy',
                'question' => 'Where can I read the full Privacy Policy?',
                'answer' => 'Visit the Privacy Policy page on SecondBook for detailed privacy information.',
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