<?php
/**
 * Template Name: VMS Gate Documentation
 *
 * This template displays comprehensive documentation for gate staff using the Visitor Management System.
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
                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mr-6">
                    <span class="text-3xl">🚪</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gate Staff Guide</h1>
                    <p class="text-gray-600">Security protocols and guest verification procedures at the entrance</p>
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Gate Staff Guide</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Overview Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                Gate Security Overview
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Your Key Responsibilities</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Verify guest identities at entrance</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Check visitor badges and access passes</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Register accommodation guests</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Manage supplier and delivery access</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                            <span class="text-sm text-gray-600">Maintain security protocols and reporting</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚪 Gate Operations</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Operating Hours:</span>
                                <span class="font-semibold text-green-600">24/7</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Peak Check-ins:</span>
                                <span class="font-semibold text-blue-600">8AM-10AM</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Current Visitors:</span>
                                <span class="font-semibold text-purple-600">23</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending Arrivals:</span>
                                <span class="font-semibold text-orange-600">5</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">⚠️ Important Notes</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Never admit guests without proper verification</li>
                            <li>• Report suspicious activity immediately</li>
                            <li>• Maintain professional conduct at all times</li>
                            <li>• Keep access logs accurate and up-to-date</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guest Verification Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                Guest Verification Process
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔍 Standard Guest Check-in</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Stop Vehicle</p>
                                <p class="text-gray-500 text-sm">Politely request vehicle to stop at gate checkpoint</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Verify Badge/Pass</p>
                                <p class="text-gray-500 text-sm">Check visitor badge or access pass legitimacy</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Match Identity</p>
                                <p class="text-gray-500 text-sm">Compare guest photo and details with presented ID</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Check Visit Status</p>
                                <p class="text-gray-500 text-sm">Verify visit is active and within scheduled time</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">5</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Grant Access</p>
                                <p class="text-gray-500 text-sm">Open gate and log entry time in system</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚫 Denial Procedures</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">A</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Invalid Badge</p>
                                <p class="text-gray-500 text-sm">Badge expired, damaged, or not recognized</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">B</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">No Reservation</p>
                                <p class="text-gray-500 text-sm">Guest not registered in system</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">C</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Identity Mismatch</p>
                                <p class="text-gray-500 text-sm">Guest doesn't match registered details</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">D</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Security Concerns</p>
                                <p class="text-gray-500 text-sm">Suspicious behavior or unauthorized items</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">E</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Report Incident</p>
                                <p class="text-gray-500 text-sm">Document denial and notify security/reception</p>
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
                            <strong>Critical:</strong> Never admit anyone without proper verification. If in doubt,
                            contact reception or security immediately. Document all access denials.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accommodation Guest Registration Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                Accommodation Guest Registration
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏨 Hotel/Accommodation Guests</h3>
                    <p class="text-gray-600 mb-4">
                        Register guests staying in club accommodations:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Verify Booking</p>
                                <p class="text-gray-500 text-sm">Check accommodation reservation details</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Collect Information</p>
                                <p class="text-gray-500 text-sm">Full name, ID, contact details, room number</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">ID Verification</p>
                                <p class="text-gray-500 text-sm">Scan and verify government-issued ID</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Register in System</p>
                                <p class="text-gray-500 text-sm">Create accommodation guest record</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">5</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Issue Access Pass</p>
                                <p class="text-gray-500 text-sm">Print visitor badge for facility access</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Required Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">Personal Details:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Full legal name</li>
                                    <li>• Date of birth</li>
                                    <li>• Nationality</li>
                                    <li>• Contact phone number</li>
                                </ul>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">Stay Information:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Check-in/check-out dates</li>
                                    <li>• Room number</li>
                                    <li>• Booking reference</li>
                                    <li>• Special requirements</li>
                                </ul>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">Identification:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Valid passport/ID</li>
                                    <li>• Guest photograph</li>
                                    <li>• Emergency contact</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supplier & Delivery Access Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                Supplier & Delivery Access
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚚 Supplier Entry Process</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Verify Appointment</p>
                                <p class="text-gray-500 text-sm">Check scheduled delivery or service time</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Driver Identification</p>
                                <p class="text-gray-500 text-sm">Verify driver's ID and company credentials</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Vehicle Inspection</p>
                                <p class="text-gray-500 text-sm">Check vehicle details and cargo manifest</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Issue Temporary Pass</p>
                                <p class="text-gray-500 text-sm">Create supplier access record</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">5</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Monitor Activity</p>
                                <p class="text-gray-500 text-sm">Track entry time and authorized areas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Supplier Checklist</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-3" checked readonly>
                                <span class="text-sm text-gray-600">Valid appointment confirmation</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-3" checked readonly>
                                <span class="text-sm text-gray-600">Driver ID and company badge</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-3" checked readonly>
                                <span class="text-sm text-gray-600">Vehicle registration check</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-3" checked readonly>
                                <span class="text-sm text-gray-600">Delivery manifest/invoice</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-3" checked readonly>
                                <span class="text-sm text-gray-600">Authorized delivery area</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-3" checked readonly>
                                <span class="text-sm text-gray-600">Time window verification</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">🚫 Restricted Items</h4>
                        <ul class="text-sm text-red-600 space-y-1">
                            <li>• Unauthorized personnel</li>
                            <li>• Large containers without manifest</li>
                            <li>• Hazardous materials</li>
                            <li>• After-hours deliveries</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reciprocation Member Access Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                Reciprocation Member Access
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🤝 Reciprocation Protocol</h3>
                    <p class="text-gray-600 mb-4">
                        Handle members from reciprocal clubs and organizations:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Verify Affiliation</p>
                                <p class="text-gray-500 text-sm">Check membership card and reciprocal club details</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Confirm Validity</p>
                                <p class="text-gray-500 text-sm">Verify membership is current and in good standing</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Register Visit</p>
                                <p class="text-gray-500 text-sm">Create reciprocation member access record</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Issue Courtesy Pass</p>
                                <p class="text-gray-500 text-sm">Provide temporary access badge</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">5</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Notify Reception</p>
                                <p class="text-gray-500 text-sm">Alert reception of reciprocation member arrival</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏛️ Reciprocal Clubs</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-3">
                            Current reciprocal club partnerships:
                        </p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Nairobi Club</span>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Mombasa Club</span>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Kisumu Club</span>
                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Pending</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Eldoret Club</span>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">📋 Verification Checklist</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Valid membership card</li>
                            <li>• Club affiliation confirmation</li>
                            <li>• Current membership status</li>
                            <li>• Proper attire and conduct</li>
                            <li>• Guest policy compliance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security & Emergency Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">6</span>
                Security Procedures & Reporting
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚨 Security Protocols</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Monitor for suspicious activity and unauthorized
                                access</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Report security concerns to control room
                                immediately</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Maintain accurate access logs and incident
                                reports</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Coordinate with security personnel for high-risk
                                situations</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">!</span>
                            <span class="text-sm text-gray-600">Follow emergency evacuation procedures when
                                required</span>
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
                                <span class="text-red-700">Reception:</span>
                                <span class="font-semibold text-red-800">Ext. 100</span>
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
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">🚫 Reportable Incidents</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Unauthorized access attempts</li>
                            <li>• Suspicious persons or vehicles</li>
                            <li>• Lost or stolen access badges</li>
                            <li>• Security system malfunctions</li>
                            <li>• Emergency situations</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Section -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">🆘 Gate Staff Support</h2>
                <p class="text-gray-600 mb-6">
                    Get help with security procedures, system access, or emergency situations.
                </p>
                <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">📋</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Procedure Manual</h3>
                        <p class="text-sm text-gray-600">Access detailed security protocols and standard operating
                            procedures</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">💬</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Security Coordinator</h3>
                        <p class="text-sm text-gray-600">Contact security supervisor for complex situations or policy
                            clarification</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">🚨</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Emergency Hotline</h3>
                        <p class="text-sm text-gray-600">Direct access to emergency services and security control room
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>