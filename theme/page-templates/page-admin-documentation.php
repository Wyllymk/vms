<?php
/**
 * Template Name: VMS Admin Documentation
 *
 * This template displays comprehensive documentation for administrators using the Visitor Management System.
 *
 * @package VMS
 * @subpackage Theme
 * @since 1.0.0
 */

get_header();
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mr-6">
                    <span class="text-3xl">⚙️</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Administrator Guide</h1>
                    <p class="text-gray-600">Complete system access and configuration management</p>
                </div>
            </div>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?php echo home_url('/vms-documentation/'); ?>"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2A1 1 0 0 0 1 10h2v9a1 1 0 0 0 1 1h6v-6h2v6h6a1 1 0 0 0 1-1v-9h2a1 1 0 0 0 .707-1.707Z" />
                            </svg>
                            Documentation Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Administrator Guide</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- System Overview Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                Administrator System Overview
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔑 Complete System Access</h3>
                    <p class="text-gray-600 mb-4">
                        Administrators have unrestricted access to all system functions, including all permissions from
                        other roles plus additional management capabilities.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">⚙️</span>
                            <span class="text-sm text-gray-600">System configuration and settings management</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">👥</span>
                            <span class="text-sm text-gray-600">User role creation and permission management</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">📊</span>
                            <span class="text-sm text-gray-600">Complete audit trail and system logs access</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">📱</span>
                            <span class="text-sm text-gray-600">SMS and email delivery monitoring</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Critical Responsibilities</h3>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="text-red-500 mr-2">⚠️</span>
                                <span class="text-sm text-red-800">System security and access control</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-red-500 mr-2">⚠️</span>
                                <span class="text-sm text-red-800">Data integrity and backup management</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-red-500 mr-2">⚠️</span>
                                <span class="text-sm text-red-800">Notification system configuration</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-red-500 mr-2">⚠️</span>
                                <span class="text-sm text-red-800">User role and permission management</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Configuration Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                System Configuration & Settings
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔧 Core Settings</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Visit Limits Configuration</p>
                                <p class="text-gray-500 text-sm">Set monthly/yearly guest limits and host capacity rules
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Notification Templates</p>
                                <p class="text-gray-500 text-sm">Customize SMS and email message templates</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">SMS Gateway Setup</p>
                                <p class="text-gray-500 text-sm">Configure SMS provider credentials and settings</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Email Configuration</p>
                                <p class="text-gray-500 text-sm">Set up SMTP settings and email delivery options</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👥 User & Role Management</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">A</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Create User Accounts</p>
                                <p class="text-gray-500 text-sm">Set up accounts for reception, gate, and admin staff
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">B</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Assign User Roles</p>
                                <p class="text-gray-500 text-sm">Configure appropriate permissions for each user type
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">C</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Permission Management</p>
                                <p class="text-gray-500 text-sm">Fine-tune access controls and role capabilities</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">D</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Account Maintenance</p>
                                <p class="text-gray-500 text-sm">Manage password resets and account status</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit & Monitoring Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                Audit Trail & System Monitoring
            </h2>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📋</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-center">Audit Logs</h3>
                    <p class="text-sm text-gray-600 text-center">
                        Complete history of all system activities, user actions, and data changes
                    </p>
                    <ul class="text-xs text-gray-500 mt-3 space-y-1">
                        <li>• User login/logout events</li>
                        <li>• Guest registration activities</li>
                        <li>• Visit status modifications</li>
                        <li>• System configuration changes</li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-center">SMS Logs</h3>
                    <p class="text-sm text-gray-600 text-center">
                        Track SMS delivery status, content, and recipient information
                    </p>
                    <ul class="text-xs text-gray-500 mt-3 space-y-1">
                        <li>• Message delivery confirmation</li>
                        <li>• Failed delivery reports</li>
                        <li>• SMS content history</li>
                        <li>• Cost and usage analytics</li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📧</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-center">Email Logs</h3>
                    <p class="text-sm text-gray-600 text-center">
                        Monitor email delivery, open rates, and notification effectiveness
                    </p>
                    <ul class="text-xs text-gray-500 mt-3 space-y-1">
                        <li>• Email delivery status</li>
                        <li>• Bounce and error reports</li>
                        <li>• Open and click tracking</li>
                        <li>• Template performance</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔍 Log Analysis & Reporting</h3>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="text-yellow-600 mr-3">📊</span>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">Advanced Analytics</p>
                            <p class="text-sm text-yellow-700 mt-1">
                                Use log data to identify usage patterns, system performance metrics, and security
                                insights.
                                Generate reports on peak usage times, most active users, and system reliability.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance & Troubleshooting Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                System Maintenance & Troubleshooting
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔧 Maintenance Tasks</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Database Optimization</p>
                                <p class="text-gray-500 text-sm">Regular cleanup of old records and performance tuning
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Backup Management</p>
                                <p class="text-gray-500 text-sm">Automated and manual backup procedures</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Plugin Updates</p>
                                <p class="text-gray-500 text-sm">Manage system updates and compatibility checks</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Data Integrity Checks</p>
                                <p class="text-gray-500 text-sm">Verify data consistency and repair corrupted records
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚨 Troubleshooting Guide</h3>
                    <div class="space-y-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h5 class="font-medium text-red-800 mb-2">Common Issues & Solutions</h5>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-red-700">SMS Not Sending</span>
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Gateway
                                        Config</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-red-700">Email Failures</span>
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">SMTP Settings</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-red-700">Login Issues</span>
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Role
                                        Permissions</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-red-700">Data Sync Errors</span>
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Database
                                        Check</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security & Compliance Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                Security & Compliance Management
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔒 Security Protocols</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">🔐</span>
                            <span class="text-sm text-gray-600">Regular password policy enforcement and updates</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">👁️</span>
                            <span class="text-sm text-gray-600">Monitor for suspicious access patterns and
                                anomalies</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">🔑</span>
                            <span class="text-sm text-gray-600">Manage API keys and third-party service
                                credentials</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">📋</span>
                            <span class="text-sm text-gray-600">Regular security audits and compliance reviews</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Data Protection</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">🛡️</span>
                                <span class="text-sm text-blue-800">GDPR compliance for personal data handling</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">🔒</span>
                                <span class="text-sm text-blue-800">Data encryption for sensitive information</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">⏰</span>
                                <span class="text-sm text-blue-800">Automated data retention and deletion
                                    policies</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">📊</span>
                                <span class="text-sm text-blue-800">Regular data backup and recovery testing</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support & Resources Section -->
        <div class="bg-gradient-to-r from-red-50 to-purple-50 rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">🆘 Administrator Support</h2>
                <p class="text-gray-600 mb-6">
                    Access advanced tools and resources for system administration and troubleshooting.
                </p>
                <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">📚</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Technical Documentation</h3>
                        <p class="text-sm text-gray-600">Complete API references, database schemas, and technical
                            specifications</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">🔧</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Developer Tools</h3>
                        <p class="text-sm text-gray-600">Debugging utilities, system diagnostics, and performance
                            monitoring</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">📞</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Priority Support</h3>
                        <p class="text-sm text-gray-600">Direct access to development team for critical system issues
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>