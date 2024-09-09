# Changelog

All notable changes to "Placetogrow" will be documented in this file.

## [0.3.0] - 2024-09-08
### Added
- Subscription payment functionality allowing administrators to create and manage subscription plans.
    - Subscription plans include: name, description, value (amount and currency), subscription expiration time, billing frequency.
    - Administrators can import invoices for users.
    - View received payments from users.
- Guest users can subscribe to available plans, manage active subscriptions, and view/pay generated invoices.
    - View list of subscription plans.
    - Manage subscriptions (cancel).
    - View invoices (pending, overdue, paid) and make payments.

## [0.2.0] - 2024-08-04
### Added
- ACL (Access Control List) functionality for role-based permission management.
- Simple payment functionalities allowing administrators to define required user information for payments.
    - Payer information: name, document type and number, email.
    - Payment configuration: currency, payment expiration time.
- Transaction list viewing for administrators across different microsites.
- Guest user functionalities to fill out forms and generate basic payment sessions.
- Input validation according to administrator-defined rules.
- Transaction summary page for users to view their transaction details.

## [0.1.0] - 2024-06-30
### Added
- Authentication functionality for administrators and role management.
- CRUD operations for managing microsites.
- Initial project setup including Laravel and InertiaJs with Vue 3.

### Changed
- Updated README with installation and setup instructions.
