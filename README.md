# Visitor Management System Theme

A custom WordPress theme designed for the Visitor Management System (VMS) plugin, built on [\_tw](https://underscoretw.com) with Tailwind CSS integration. Provides a clean, modern interface for managing club visitors and staff operations.

## 🎯 System Overview

The Visitor Management System (VMS) is a comprehensive solution for managing visitors, guests, and access control in club environments. The system supports multiple user roles with specific permissions and capabilities, ensuring secure and efficient visitor management.

### Key Features

- **Multi-role User System**: Support for Members, Chairman, General Manager, Reception, Gate, and Admin roles
- **Guest Registration & Tracking**: Register guests with visit dates, host information, and status tracking
- **Automated Notifications**: SMS and email notifications for visit updates and status changes
- **Visit Limits & Enforcement**: Automatic enforcement of monthly (4 visits) and yearly (24 visits) limits
- **Real-time Sign-in/Sign-out**: Track guest arrival and departure times
- **Comprehensive Reporting**: Detailed reports and analytics for management
- **Audit Trail**: Complete logging of all system activities

### How the System Works

#### 📋 **Visit Registration Process**

1. **Member Registration**: New members register and are approved by reception staff
2. **Guest Registration**: Approved members can register guests for specific visit dates
3. **System Validation**: Automatic checks for visit limits and availability
4. **Approval Process**: Visits are approved based on capacity and guest status
5. **Notifications**: All parties receive SMS/email confirmations
6. **Sign-in/Sign-out**: Reception handles physical check-in with ID verification
7. **Reporting**: Complete tracking and analytics for management

#### 🔄 **Visit Status Flow**

```
Registration → Approval → Confirmed → Signed In → Signed Out → Completed
     ↓           ↓         ↓          ↓          ↓           ↓
  Rejected   Unapproved  Cancelled  (Auto)     (Auto)    (Archived)
```

#### 📊 **Automated Limits**

- **Guest Limits**: 4 visits/month, 24 visits/year per guest
- **Host Limits**: 4 guests per day per host member
- **Status Management**: Automatic suspension for limit violations
- **Reconciliation**: Monthly/yearly limit resets

## 🚀 Quickstart

### 1. Installation

1. Move this folder to your local WordPress installation under:
    ```
    wp-content/themes
    ```
2. Install dependencies and build assets:
    ```bash
    npm install && npm run dev
    ```
3. Activate this theme via **Appearance → Themes** in your WordPress dashboard.

> **For WordPress Multisite**  
> Make sure to enable this theme via the **Network Admin** before activating on any subsite.

### 2. Development

Run the watcher for live updates:

```bash
npm run watch
```

You can now edit your theme files, SCSS/Tailwind classes, and JavaScript. Changes will automatically recompile.

### 3. Deployment

1. Build for production:
    ```bash
    npm run bundle
    ```
2. Upload the resulting **ZIP file** via **Appearance → Themes → Add New → Upload Theme**.

## 📖 User Role Documentation

### 🔐 **Member Guide**

#### Getting Started

1. **Register**: Create your member account through the registration page
2. **Approval**: Wait for reception approval (you'll receive SMS/email confirmation)
3. **Access**: Once approved, you can register guests and manage visits

#### Guest Registration Process

1. Navigate to "Register Guest" in your dashboard
2. Fill in guest details:
    - First Name, Last Name
    - Phone Number (required for SMS notifications)
    - Email (optional, for email notifications)
    - Visit Date (must be future date)
3. Review and submit
4. Receive confirmation SMS/email with visit status

#### Managing Your Guests

- **View Visits**: See all your registered guests and their visit status
- **Cancel Visits**: Cancel upcoming visits (guest will be notified)
- **Visit History**: Track past visits and attendance

#### Important Notes

- **Visit Limits**: Maximum 4 visits per month, 24 per year per guest
- **Host Limits**: You can only host 4 guests per day
- **Notifications**: You'll receive updates for all visit status changes

---

### 👑 **Chairman Guide**

#### Overview

As Chairman, you have elevated privileges for guest management and system oversight. You can register both personal guests and courtesy guests, plus access comprehensive system reports.

#### Guest Registration

1. **Personal Guests**: Register guests with yourself as host
2. **Courtesy Guests**: Register guests who don't need a specific host
3. **Direct Approval**: Your registrations are automatically approved

#### System Oversight

- **Reports Dashboard**: Access comprehensive visitor analytics
- **Supplier Management**: View and edit supplier information
- **Accommodation Data**: Manage accommodation provider records
- **Reciprocation Members**: Access reciprocal club member data
- **Club Information**: View and edit club-related data

#### Key Responsibilities

- Monitor overall system usage and visitor patterns
- Approve special visit requests when needed
- Maintain supplier and partner relationships
- Ensure system runs smoothly for all users

---

### 🏢 **General Manager Guide**

#### Administrative Duties

As General Manager, you handle high-level administrative tasks and system monitoring. You can register courtesy guests and access comprehensive system reports.

#### Courtesy Guest Management

- Register guests who don't have specific hosts
- Handle special cases and VIP visits
- Manage organization-wide guest access

#### System Monitoring

- **Reports Access**: Review detailed analytics on visitor patterns
- **Performance Metrics**: Monitor system usage and efficiency
- **Capacity Planning**: Track visit trends and plan for peak times

#### Data Management

- **Supplier Records**: Maintain supplier contact information
- **Accommodation Providers**: Manage accommodation partner data
- **Reciprocation Data**: Handle reciprocal club member access
- **Club Operations**: Oversee club-related operational data

---

### 🚪 **Gate Staff Guide**

#### Physical Access Control

Gate staff handle physical access control and verification upon arrival. You cannot register regular guests - all sign-ins must be handled by reception.

#### What You Can Do

- **Register Accommodation Guests**: Walk-in guests staying at accommodations
- **Register Suppliers**: Delivery personnel and service providers
- **Register Reciprocation Members**: Members from partner clubs
- **Access Control**: Manage physical entry based on registration status

#### Important Restrictions

- **Cannot register regular guests** arriving at the gate (must be pre-registered)
- **Cannot perform sign-in operations** (handled exclusively by reception)
- **All sign-in is done at reception** with proper ID verification

#### Daily Operations

1. **Arrival Verification**: Check pre-registered guests against ID/passport
2. **Accommodation Guests**: Register walk-in accommodation visitors
3. **Supplier Registration**: Process suppliers and service providers
4. **Reciprocation Members**: Handle members from reciprocal clubs
5. **Access Management**: Control physical entry based on system status

---

### 🏨 **Reception Staff Guide**

#### Primary Contact Point

Reception staff are the main point of contact for visitor management. You handle member approvals, guest registration, and all sign-in/sign-out operations.

#### Member Management

1. **Review Applications**: Access pending member registration requests
2. **Approval Process**: Approve or reject member applications
3. **Notification**: Members receive SMS/email about approval status

#### Guest Registration

- **On Behalf of Members**: Register guests for members who call ahead
- **Direct Registration**: Handle special cases and walk-ins
- **Information Updates**: Edit and update guest information as needed

#### Sign-in/Sign-out Process

1. **Guest Arrival**: Verify guest identity against registration
2. **ID Verification**: Check ID number/passport against system records
3. **Sign In**: Record arrival time with timestamp
4. **Sign Out**: Record departure time automatically or manually
5. **Notifications**: Send confirmations to guest and host

#### System Maintenance

- **Data Updates**: Keep member and guest information current
- **Visit Management**: Handle cancellations and modifications
- **Supplier Records**: Maintain supplier contact information
- **Status Monitoring**: Track system status and resolve issues

---

### ⚙️ **Administrator Guide**

#### Complete System Access

Administrators have full access to all system functions and configuration options. This includes all permissions from other roles plus additional system management capabilities.

#### System Configuration

- **Settings Management**: Configure system-wide settings and limits
- **Notification Setup**: Configure SMS gateway and email templates
- **User Role Management**: Create and manage user accounts and permissions
- **System Preferences**: Set up notification preferences and rules

#### Audit & Monitoring

- **Audit Logs**: Complete system activity history
- **SMS Logs**: Track all SMS delivery status and content
- **Email Logs**: Monitor email delivery and open rates
- **System Analytics**: Comprehensive reporting on all system activities

#### Maintenance Tasks

- **User Management**: Create accounts and assign roles
- **System Updates**: Perform updates and maintenance
- **Backup Management**: Handle system backups and recovery
- **Troubleshooting**: Resolve system issues and conflicts

## 📚 Documentation Templates

This theme includes comprehensive documentation templates accessible via:

- `/vms-documentation/member/` - Member user guide
- `/vms-documentation/chairman/` - Chairman operations guide
- `/vms-documentation/general-manager/` - General Manager handbook
- `/vms-documentation/gate/` - Gate staff procedures
- `/vms-documentation/reception/` - Reception staff manual
- `/vms-documentation/admin/` - Administrator system guide
- `/vms-documentation/overview/` - System overview and workflow

## 🛠 Development Workflow

- Tailwind classes can be added directly to PHP templates, block patterns, or JavaScript-rendered HTML.
- All theme-specific assets are compiled from `/src` into `/dist`.
- Page templates follow the WordPress `page-templates` standard for custom layouts.

## 📖 Technical Documentation

### Fundamentals

- **[Installation](https://underscoretw.com/docs/installation/)** – How to set up and run your first Tailwind build.
- **[Development](https://underscoretw.com/docs/development/)** – Watching changes, hot reload, and development workflow.
- **[Deployment](https://underscoretw.com/docs/deployment/)** – Best practices for releasing your theme.

### In Depth

- **[Using Tailwind Typography](https://underscoretw.com/docs/tailwind-typography/)** – Typographic customization for front-end and back-end.
- **[JavaScript Bundling with esbuild](https://underscoretw.com/docs/esbuild/)** – Using esbuild for fast JS compilation.
- **[Adding Custom Fonts](https://underscoretw.com/docs/custom-fonts/)** – How to self-host or use third-party fonts.
- **[Linting & Formatting](https://underscoretw.com/docs/linting-code-formatting/)** – Keep your code clean and bug-free.

### System Integration

- **[VMS Plugin Integration](https://github.com/wyllymk/vms-plugin)** – Complete plugin documentation
- **[WordPress Roles](https://wordpress.org/support/article/roles-and-capabilities/)** – Understanding user roles
- **[Custom Post Types](https://developer.wordpress.org/plugins/post-types/)** – System data structure

## 📌 Notes

- This theme is specifically designed for the Visitor Management System plugin
- All branding and CSS come from this theme to ensure style consistency
- Uses WordPress roles and capabilities to manage dashboard and access control
- Templates are optimized for the VMS workflow and user experience

## 🆘 Support

For support and additional documentation:

- [Plugin Documentation](https://github.com/wyllymk/vms-plugin/wiki)
- [Theme Documentation](https://github.com/wyllymk/vms-theme)
- [WordPress Support](https://wordpress.org/support/)
- [VMS User Guides](https://yourdomain.com/vms-documentation/)

---

**Author:** Wilson Mbuthia  
**License:** GPLv2 or later  
**Version:** 1.0.0
