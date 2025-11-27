<?php
/**
 * Template Name: VMS Documentation Overview
 *
 * This template displays the main documentation overview page for the Visitor Management System.
 *
 * @package VMS
 * @subpackage Theme
 * @since 1.0.0
 */

get_header();
?>

<div class="min-h-screen bg-gray-50 p-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Visitor Management System Documentation
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Comprehensive guides and user manuals for all system users. Learn how to effectively use the Visitor
                Management System.
            </p>
        </div>

        <!-- System Overview Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">🎯 System Overview</h2>
                    <p class="text-gray-600 mb-4">
                        The Visitor Management System (VMS) provides a complete solution for managing visitors, guests,
                        and access control in club environments.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                            <span class="text-sm text-gray-600">Multi-role user system with specific permissions</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                            <span class="text-sm text-gray-600">Automated visit limits and notifications</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                            <span class="text-sm text-gray-600">Real-time sign-in/sign-out tracking</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                            <span class="text-sm text-gray-600">Comprehensive reporting and analytics</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 How It Works</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <span class="text-sm text-gray-600">Members register and get approved by reception</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <span class="text-sm text-gray-600">Approved members register guests for future
                                visits</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <span class="text-sm text-gray-600">System validates limits and availability</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <span class="text-sm text-gray-600">Reception handles sign-in with ID verification</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Role Documentation Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <!-- Member Documentation -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">🔐</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Member Guide</h3>
                        <p class="text-sm text-gray-600">Guest registration & management</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Learn how to register guests, manage visits, and track your visit history within the monthly and
                    yearly limits.
                </p>
                <a href="<?php echo home_url('/vms-documentation/member/'); ?>"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    Read Member Guide →
                </a>
            </div>

            <!-- Chairman Documentation -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">👑</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Chairman Guide</h3>
                        <p class="text-sm text-gray-600">Executive oversight & management</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Access comprehensive reports, register courtesy guests, and oversee system operations and supplier
                    management.
                </p>
                <a href="<?php echo home_url('/vms-documentation/chairman/'); ?>"
                    class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium">
                    Read Chairman Guide →
                </a>
            </div>

            <!-- General Manager Documentation -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">🏢</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">General Manager</h3>
                        <p class="text-sm text-gray-600">Administrative oversight</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Monitor system performance, manage courtesy guests, and oversee supplier and accommodation data.
                </p>
                <a href="<?php echo home_url('/vms-documentation/general-manager/'); ?>"
                    class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
                    Read GM Guide →
                </a>
            </div>

            <!-- Reception Documentation -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">🏨</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Reception Staff</h3>
                        <p class="text-sm text-gray-600">Front desk operations</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Handle member approvals, guest registration, sign-in/sign-out operations, and system maintenance.
                </p>
                <a href="<?php echo home_url('/vms-documentation/reception/'); ?>"
                    class="inline-flex items-center text-yellow-600 hover:text-yellow-800 font-medium">
                    Read Reception Guide →
                </a>
            </div>

            <!-- Gate Documentation -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">🚪</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Gate Staff</h3>
                        <p class="text-sm text-gray-600">Access control & verification</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Manage physical access, register accommodation guests and suppliers, handle reciprocation members.
                </p>
                <a href="<?php echo home_url('/vms-documentation/gate/'); ?>"
                    class="inline-flex items-center text-orange-600 hover:text-orange-800 font-medium">
                    Read Gate Guide →
                </a>
            </div>

            <!-- Admin Documentation -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">⚙️</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Administrator</h3>
                        <p class="text-sm text-gray-600">System configuration</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Complete system access, audit logs, SMS/email logs, settings configuration, and maintenance tasks.
                </p>
                <a href="<?php echo home_url('/vms-documentation/admin/'); ?>"
                    class="inline-flex items-center text-red-600 hover:text-red-800 font-medium">
                    Read Admin Guide →
                </a>
            </div>
        </div>

        <!-- System Features Overview -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">🚀 Key System Features</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">SMS Notifications</h3>
                    <p class="text-sm text-gray-600">Automated SMS alerts for visit status updates and confirmations</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📧</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Email Integration</h3>
                    <p class="text-sm text-gray-600">Email notifications and detailed visit information</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📊</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Analytics & Reports</h3>
                    <p class="text-sm text-gray-600">Comprehensive reporting for management insights</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🔒</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Access Control</h3>
                    <p class="text-sm text-gray-600">Role-based permissions and secure guest verification</p>
                </div>
            </div>
        </div>

        <!-- Quick Start Guide -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">⚡ Quick Start Guide</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                        1</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Install & Setup</h3>
                    <p class="text-sm text-gray-600">Install the plugin and theme, configure SMS gateway and user roles
                    </p>
                </div>
                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                        2</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Create User Accounts</h3>
                    <p class="text-sm text-gray-600">Set up reception, gate, and administrative staff accounts</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                        3</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Start Managing Visitors</h3>
                    <p class="text-sm text-gray-600">Begin member registrations and guest visit management</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>