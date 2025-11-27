<?php
/**
 * Template Name: VMS Member Documentation
 *
 * This template displays comprehensive documentation for members using the Visitor Management System.
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
                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mr-6">
                    <span class="text-3xl">🔐</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Member Guide</h1>
                    <p class="text-gray-600">Complete guide for club members on using the Visitor Management System</p>
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Member Guide</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Getting Started Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                Getting Started as a Member
            </h2>

            <div class="grid md:grid-cols-2 gap-8 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Registration Process</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Visit Registration Page</p>
                                <p class="text-gray-500 text-sm">Navigate to the member registration page and fill out
                                    the required form with your personal details.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Submit Application</p>
                                <p class="text-gray-500 text-sm">Provide all required information including contact
                                    details, membership number, and identification.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Await Approval</p>
                                <p class="text-gray-500 text-sm">Reception staff will review your application and
                                    approve your membership within 24 hours.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Receive Login Credentials</p>
                                <p class="text-gray-500 text-sm">Once approved, you'll receive login credentials via SMS
                                    and email to access your member dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚠️ Important Notes</h3>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <ul class="text-sm text-yellow-700 space-y-2">
                                    <li>• You must be approved by reception before you can register guests</li>
                                    <li>• Keep your contact information updated for notifications</li>
                                    <li>• Your membership has monthly and yearly guest limits</li>
                                    <li>• All guests must be registered at least 24 hours in advance</li>
                                    <li>• You can only cancel visits up to 2 hours before the scheduled time</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">📊 Visit Limits</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Monthly Limit:</span>
                                    <span class="font-semibold text-gray-900 ml-2">20 guests</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Yearly Limit:</span>
                                    <span class="font-semibold text-gray-900 ml-2">150 guests</span>
                                </div>
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
                Registering Guests
            </h2>

            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Once you're approved as a member, you can register guests for future visits. Follow these steps to
                    register a guest:
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">👤</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Step 1: Access Guest Registration</h3>
                    <p class="text-sm text-gray-600">Log in to your member dashboard and navigate to the "Register
                        Guest" section</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📝</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Step 2: Fill Guest Details</h3>
                    <p class="text-sm text-gray-600">Enter guest's full name, phone number, ID number, and select visit
                        date and time</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">✅</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Step 3: Submit & Confirm</h3>
                    <p class="text-sm text-gray-600">Review details, submit the registration, and receive SMS
                        confirmation</p>
                </div>
            </div>

            <div class="mt-8 bg-blue-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-3">📋 Required Guest Information</h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Full Name (as per ID document)</li>
                        <li>• Phone Number (for SMS notifications)</li>
                        <li>• ID Number/Passport Number</li>
                        <li>• Visit Date and Time</li>
                    </ul>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Guest Type (Regular/Visitor)</li>
                        <li>• Purpose of Visit</li>
                        <li>• Any special requirements</li>
                        <li>• Emergency contact information</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Managing Visits Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                Managing Your Visits
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👁️ Viewing Guest Details</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">A</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Access Guest List</p>
                                <p class="text-gray-500 text-sm">Go to "My Guests" section in your dashboard to see all
                                    registered guests</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">B</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">View Guest Details</p>
                                <p class="text-gray-500 text-sm">Click on any guest to view complete information
                                    including visit status and history</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">C</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Check Visit Status</p>
                                <p class="text-gray-500 text-sm">Monitor if your guest has arrived, is currently
                                    visiting, or has departed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">❌ Cancelling Visits</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Select Guest</p>
                                <p class="text-gray-500 text-sm">Find the guest in your list whose visit you want to
                                    cancel</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">View Details</p>
                                <p class="text-gray-500 text-sm">Click to view the guest's visit details and options</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Cancel Visit</p>
                                <p class="text-gray-500 text-sm">Click "Cancel Visit" button (available up to 2 hours
                                    before scheduled time)</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Confirm Cancellation</p>
                                <p class="text-gray-500 text-sm">Confirm the cancellation and receive SMS notification
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-red-400">⚠️</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <strong>Cancellation Policy:</strong> Visits can only be cancelled up to 2 hours before the
                            scheduled visit time. Late cancellations may result in loss of guest slots for that month.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visit History & Tracking Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                Visit History & Limits
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Tracking Your Usage</h3>
                    <p class="text-gray-600 mb-4">
                        Monitor your monthly and yearly guest visit limits through your dashboard:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                            <span class="text-sm font-medium text-gray-700">This Month's Guests:</span>
                            <span class="text-sm font-bold text-blue-600">12/20</span>
                        </div>
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                            <span class="text-sm font-medium text-gray-700">This Year's Guests:</span>
                            <span class="text-sm font-bold text-green-600">87/150</span>
                        </div>
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                            <span class="text-sm font-medium text-gray-700">Available Slots:</span>
                            <span class="text-sm font-bold text-purple-600">8</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Visit History</h3>
                    <p class="text-gray-600 mb-4">
                        Access your complete visit history to track past activities:
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• View all registered guests and their visit dates</li>
                        <li>• Check arrival and departure times</li>
                        <li>• See cancelled or missed visits</li>
                        <li>• Export visit history reports</li>
                        <li>• Filter by date range or guest name</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Notifications & Communication Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                Notifications & Communication
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📱 SMS Notifications</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Guest registration confirmation</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Visit approval notifications</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Visit reminders (24 hours before)</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Visit status updates</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Cancellation confirmations</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📧 Email Communications</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✉</span>
                            <span class="text-sm text-gray-600">Detailed visit confirmations</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✉</span>
                            <span class="text-sm text-gray-600">Monthly usage reports</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✉</span>
                            <span class="text-sm text-gray-600">Important system announcements</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✉</span>
                            <span class="text-sm text-gray-600">Visit history summaries</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✉</span>
                            <span class="text-sm text-gray-600">Account and security updates</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Troubleshooting Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">6</span>
                Troubleshooting & FAQ
            </h2>

            <div class="space-y-6">
                <div class="border-l-4 border-orange-400 bg-orange-50 p-4">
                    <h4 class="font-semibold text-orange-900 mb-2">❓ Why can't I register a guest?</h4>
                    <p class="text-orange-800 text-sm">
                        You may have reached your monthly or yearly limit, or your membership hasn't been approved yet.
                        Check your dashboard for current limits and approval status.
                    </p>
                </div>

                <div class="border-l-4 border-blue-400 bg-blue-50 p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">❓ How do I change a guest's visit date?</h4>
                    <p class="text-blue-800 text-sm">
                        Cancel the current visit and register a new one with the updated date. Make sure to do this at
                        least 24 hours before the original visit time.
                    </p>
                </div>

                <div class="border-l-4 border-green-400 bg-green-50 p-4">
                    <h4 class="font-semibold text-green-900 mb-2">❓ I didn't receive an SMS confirmation. What should I
                        do?</h4>
                    <p class="text-green-800 text-sm">
                        Contact reception staff or check your dashboard for the registration status. SMS delivery can
                        sometimes be delayed due to network issues.
                    </p>
                </div>

                <div class="border-l-4 border-red-400 bg-red-50 p-4">
                    <h4 class="font-semibold text-red-900 mb-2">❓ What happens if my guest arrives late?</h4>
                    <p class="text-red-800 text-sm">
                        Late arrivals should still be registered at reception. However, frequent late arrivals may
                        affect your guest privileges.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Support Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">🆘 Need Help?</h2>
                <p class="text-gray-600 mb-6">
                    If you need assistance or have questions about using the system, don't hesitate to contact our
                    support team.
                </p>
                <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">📞</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Phone Support</h3>
                        <p class="text-sm text-gray-600">Call reception during business hours</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">✉️</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Email Support</h3>
                        <p class="text-sm text-gray-600">Send detailed queries to support</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">💬</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Dashboard Help</h3>
                        <p class="text-sm text-gray-600">Access help sections in your member dashboard</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>