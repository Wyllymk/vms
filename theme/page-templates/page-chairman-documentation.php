<?php
/**
 * Template Name: VMS Chairman Documentation
 *
 * This template displays comprehensive documentation for the Chairman using the Visitor Management System.
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
                <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mr-6">
                    <span class="text-3xl">👑</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Chairman Guide</h1>
                    <p class="text-gray-600">Executive oversight and management guide for the Visitor Management System
                    </p>
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Chairman Guide</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Overview Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                Executive Overview
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Your Responsibilities</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Register personal guests and courtesy guests</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Access comprehensive system reports</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Monitor supplier and club data</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Review reciprocation member activities</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Oversee system performance and security</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Key Metrics Access</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Monthly Visitors:</span>
                                <span class="font-semibold text-purple-600">1,247</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Active Members:</span>
                                <span class="font-semibold text-purple-600">156</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">System Utilization:</span>
                                <span class="font-semibold text-purple-600">89%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending Approvals:</span>
                                <span class="font-semibold text-purple-600">12</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guest Registration Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                Guest Registration
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👤 Personal Guest Registration</h3>
                    <p class="text-gray-600 mb-4">
                        As Chairman, you can register guests for your personal visits with unlimited slots and priority
                        approval:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Access Registration</p>
                                <p class="text-gray-500 text-sm">Navigate to "My Guests" → "Register Guest" in your
                                    dashboard</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Select Guest Type</p>
                                <p class="text-gray-500 text-sm">Choose "Personal Guest" or "Courtesy Guest" from the
                                    dropdown</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Fill Details</p>
                                <p class="text-gray-500 text-sm">Enter guest information and select visit date/time</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Instant Approval</p>
                                <p class="text-gray-500 text-sm">Your registrations receive automatic approval</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎩 Courtesy Guest Registration</h3>
                    <p class="text-gray-600 mb-4">
                        Register courtesy guests for official club business, diplomatic visits, or special occasions:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">A</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Purpose Selection</p>
                                <p class="text-gray-500 text-sm">Specify the official purpose of the visit</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">B</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">VIP Status</p>
                                <p class="text-gray-500 text-sm">Mark as VIP for enhanced security protocols</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">C</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Special Arrangements</p>
                                <p class="text-gray-500 text-sm">Request special parking, security, or facilities</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">D</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Notification Override</p>
                                <p class="text-gray-500 text-sm">Bypass standard notification delays for urgent visits
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-purple-50 border-l-4 border-purple-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-purple-400">👑</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-purple-700">
                            <strong>Executive Privilege:</strong> As Chairman, you have unlimited guest registration
                            capacity and can override standard system limitations for official purposes.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports & Analytics Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                Reports & Analytics
            </h2>

            <div class="grid md:grid-cols-3 gap-6 mb-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📊</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Visitor Reports</h3>
                    <p class="text-sm text-gray-600">Daily, weekly, and monthly visitor statistics and trends</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">👥</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Member Activity</h3>
                    <p class="text-sm text-gray-600">Member registration patterns and guest frequency analysis</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🏢</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Facility Usage</h3>
                    <p class="text-sm text-gray-600">Peak hours, capacity utilization, and space optimization</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-4">📈 Available Reports</h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Executive Summary Dashboard</li>
                        <li>• Membership Growth Report</li>
                        <li>• Peak Usage Analysis</li>
                        <li>• Security Incident Reports</li>
                        <li>• Financial Impact Analysis</li>
                    </ul>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Supplier Visit Patterns</li>
                        <li>• Reciprocation Member Activity</li>
                        <li>• System Performance Metrics</li>
                        <li>• Compliance and Audit Reports</li>
                        <li>• Custom Date Range Analysis</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Supplier & Club Data Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                Supplier & Club Management
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏪 Supplier Oversight</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">View Supplier List</p>
                                <p class="text-gray-500 text-sm">Access complete supplier database with visit history
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Monitor Frequency</p>
                                <p class="text-gray-500 text-sm">Track supplier visit patterns and frequency analysis
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Quality Assessment</p>
                                <p class="text-gray-500 text-sm">Review supplier performance and service quality</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Contract Management</p>
                                <p class="text-gray-500 text-sm">Monitor contract compliance and renewal schedules</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏛️ Club Data Management</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">A</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Member Database</p>
                                <p class="text-gray-500 text-sm">Access complete member information and activity history
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">B</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Event Coordination</p>
                                <p class="text-gray-500 text-sm">View scheduled events and special function planning</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">C</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Facility Management</p>
                                <p class="text-gray-500 text-sm">Monitor facility usage and maintenance schedules</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">D</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Financial Overview</p>
                                <p class="text-gray-500 text-sm">Review membership fees and operational costs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reciprocation Members Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                Reciprocation Member Oversight
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🤝 Reciprocation Tracking</h3>
                    <p class="text-gray-600 mb-4">
                        Monitor visits from members of reciprocal clubs and organizations:
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• View visiting member profiles and affiliations</li>
                        <li>• Track reciprocal visit patterns and frequency</li>
                        <li>• Monitor compliance with reciprocity agreements</li>
                        <li>• Generate reciprocity reports for board meetings</li>
                        <li>• Coordinate special reciprocal events</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Management Actions</h3>
                    <p class="text-gray-600 mb-4">
                        Executive actions available for reciprocation oversight:
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Approve special reciprocal arrangements</li>
                        <li>• Review reciprocity agreement renewals</li>
                        <li>• Authorize courtesy extensions for reciprocal members</li>
                        <li>• Generate diplomatic and VIP visit reports</li>
                        <li>• Coordinate with other club chairmen</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Security & Emergency Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">6</span>
                Security & Emergency Procedures
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚨 Emergency Access</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Emergency override capabilities for urgent
                                situations</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Direct communication with security personnel</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">System lockdown authorization for security
                                threats</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Emergency guest registration bypass</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📞 Emergency Contacts</h3>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-red-700">Security Control:</span>
                                <span class="font-semibold text-red-800">Ext. 911</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-700">Medical Emergency:</span>
                                <span class="font-semibold text-red-800">Ext. 912</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-700">Fire Department:</span>
                                <span class="font-semibold text-red-800">Ext. 913</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-700">General Manager:</span>
                                <span class="font-semibold text-red-800">Ext. 100</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Support Section -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">👑 Chairman Support</h2>
                <p class="text-gray-600 mb-6">
                    As Chairman, you have priority access to technical support and system enhancements.
                </p>
                <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">⚡</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Priority Support</h3>
                        <p class="text-sm text-gray-600">24/7 technical support with dedicated system administrator</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">📊</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Custom Reports</h3>
                        <p class="text-sm text-gray-600">Request custom analytics and reporting features</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">🚀</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">System Enhancements</h3>
                        <p class="text-sm text-gray-600">Suggest and prioritize new system features</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>