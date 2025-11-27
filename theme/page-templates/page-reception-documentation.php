<?php
/**
 * Template Name: VMS Reception Documentation
 *
 * This template displays comprehensive documentation for reception staff using the Visitor Management System.
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
                <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center mr-6">
                    <span class="text-3xl">🏨</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reception Staff Guide</h1>
                    <p class="text-gray-600">Complete guide for front desk operations and visitor management</p>
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Reception Guide</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Overview Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                Reception Overview
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Your Key Responsibilities</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Approve and manage member registrations</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Handle guest check-in and check-out processes</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Verify guest identities and manage access</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Maintain member and guest records</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Monitor system activity and handle inquiries</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Daily Dashboard</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending Approvals:</span>
                                <span class="font-semibold text-yellow-600">8</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Today's Check-ins:</span>
                                <span class="font-semibold text-green-600">23</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Active Visitors:</span>
                                <span class="font-semibold text-blue-600">15</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Late Arrivals:</span>
                                <span class="font-semibold text-red-600">2</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Management Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                Member Management
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👥 Member Approval Process</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Review Applications</p>
                                <p class="text-gray-500 text-sm">Access pending member applications in your dashboard
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Verify Information</p>
                                <p class="text-gray-500 text-sm">Check membership details, contact information, and
                                    documentation</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Approve or Reject</p>
                                <p class="text-gray-500 text-sm">Approve valid applications or reject with reason</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Send Notifications</p>
                                <p class="text-gray-500 text-sm">System automatically sends approval/rejection
                                    notifications</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Member Record Updates</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">A</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Update Contact Info</p>
                                <p class="text-gray-500 text-sm">Modify phone numbers, email addresses, and addresses
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">B</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Membership Status</p>
                                <p class="text-gray-500 text-sm">Update membership status, expiry dates, or privileges
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">C</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Document Management</p>
                                <p class="text-gray-500 text-sm">Upload or update membership documents and photos</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">D</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Notes & Comments</p>
                                <p class="text-gray-500 text-sm">Add internal notes about member interactions or issues
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guest Check-in/Out Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                Guest Check-in & Check-out
            </h2>

            <div class="grid md:grid-cols-2 gap-8 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚪 Guest Check-in Process</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Verify Booking</p>
                                <p class="text-gray-500 text-sm">Search for guest using name, phone, or booking
                                    reference</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">ID Verification</p>
                                <p class="text-gray-500 text-sm">Check government-issued ID and match with booking
                                    details</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Photo Capture</p>
                                <p class="text-gray-500 text-sm">Take guest photo for security and identification</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Issue Access Pass</p>
                                <p class="text-gray-500 text-sm">Print visitor badge and record check-in time</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">5</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Notify Member</p>
                                <p class="text-gray-500 text-sm">Send SMS notification to sponsoring member</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👋 Guest Check-out Process</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Locate Guest Record</p>
                                <p class="text-gray-500 text-sm">Find active guest using badge number or name</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Verify Identity</p>
                                <p class="text-gray-500 text-sm">Confirm guest identity matches check-in record</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Collect Access Pass</p>
                                <p class="text-gray-500 text-sm">Retrieve visitor badge and mark as returned</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Record Departure</p>
                                <p class="text-gray-500 text-sm">Update system with check-out time and status</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">5</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Send Confirmation</p>
                                <p class="text-gray-500 text-sm">Send departure confirmation to member and guest</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-yellow-400">⚠️</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Important:</strong> Never allow check-in without proper ID verification. Report any
                            suspicious activity to security immediately.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guest Registration on Behalf Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                Guest Registration on Behalf of Members
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Registering Guests for Members</h3>
                    <p class="text-gray-600 mb-4">
                        When members call or visit reception to register guests:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Verify Member</p>
                                <p class="text-gray-500 text-sm">Confirm member identity and current status</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Check Limits</p>
                                <p class="text-gray-500 text-sm">Verify member hasn't exceeded monthly/annual limits</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Collect Details</p>
                                <p class="text-gray-500 text-sm">Gather guest information from member</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Create Booking</p>
                                <p class="text-gray-500 text-sm">Enter guest details and select visit date/time</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">5</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Confirm & Notify</p>
                                <p class="text-gray-500 text-sm">Send confirmation SMS to member and guest</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔄 Managing Guest Visits</h3>
                    <p class="text-gray-600 mb-4">
                        Handle updates and modifications to guest bookings:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">A</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">View Guest Details</p>
                                <p class="text-gray-500 text-sm">Access complete guest information and visit history</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">B</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Update Information</p>
                                <p class="text-gray-500 text-sm">Modify guest details, dates, or special requirements
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">C</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Cancel Visits</p>
                                <p class="text-gray-500 text-sm">Process cancellation requests with proper authorization
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">D</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Handle Complaints</p>
                                <p class="text-gray-500 text-sm">Address guest or member concerns and issues</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">E</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Generate Reports</p>
                                <p class="text-gray-500 text-sm">Create visit summaries and activity reports</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Monitoring Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                System Monitoring & Maintenance
            </h2>

            <div class="grid md:grid-cols-3 gap-6 mb-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📊</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Daily Reports</h3>
                    <p class="text-sm text-gray-600">Monitor visitor traffic, peak hours, and system performance</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🚨</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Issue Handling</h3>
                    <p class="text-sm text-gray-600">Address system errors, failed SMS deliveries, and technical issues
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🔧</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Data Maintenance</h3>
                    <p class="text-sm text-gray-600">Update records, clean old data, and maintain system integrity</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-4">📋 Daily Reception Checklist</h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Review pending member approvals</li>
                        <li>• Check system status and connectivity</li>
                        <li>• Monitor SMS delivery status</li>
                        <li>• Verify ID scanning equipment</li>
                        <li>• Update member contact information</li>
                    </ul>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Process morning check-ins</li>
                        <li>• Handle member inquiries</li>
                        <li>• Monitor active visitor count</li>
                        <li>• Prepare daily activity reports</li>
                        <li>• Coordinate with security and housekeeping</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Emergency Procedures Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">6</span>
                Emergency Procedures & Security
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚨 Emergency Protocols</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Evacuation procedures and emergency exits</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Medical emergency response coordination</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Security breach reporting and response</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">System lockdown procedures</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Emergency contact protocols</span>
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
                                <span class="text-red-700">Police Emergency:</span>
                                <span class="font-semibold text-red-800">Ext. 999</span>
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

        <!-- Support Section -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">🆘 Reception Support</h2>
                <p class="text-gray-600 mb-6">
                    Get help with system issues, training, or technical support for reception operations.
                </p>
                <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">📚</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Training Resources</h3>
                        <p class="text-sm text-gray-600">Access training manuals, video tutorials, and quick reference
                            guides</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">💬</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Technical Support</h3>
                        <p class="text-sm text-gray-600">Get help with system issues, error messages, and technical
                            problems</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">📞</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Help Desk</h3>
                        <p class="text-sm text-gray-600">Contact IT support for urgent system issues and hardware
                            problems</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>