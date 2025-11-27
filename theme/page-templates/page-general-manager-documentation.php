<?php
/**
 * Template Name: VMS General Manager Documentation
 *
 * This template displays comprehensive documentation for General Managers using the Visitor Management System.
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
                    <span class="text-3xl">🏢</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">General Manager Guide</h1>
                    <p class="text-gray-600">High-level administrative oversight and courtesy guest management</p>
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">General Manager Guide</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- System Overview Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                General Manager Overview
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏢 Administrative Authority</h3>
                    <p class="text-gray-600 mb-4">
                        General Managers hold high-level administrative privileges with access to comprehensive system
                        reports and partner management capabilities.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">👥</span>
                            <span class="text-sm text-gray-600">Courtesy guest registration and management</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">📊</span>
                            <span class="text-sm text-gray-600">Complete system analytics and reporting access</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">🤝</span>
                            <span class="text-sm text-gray-600">Supplier and partner relationship management</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">🏨</span>
                            <span class="text-sm text-gray-600">Accommodation guest oversight</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Strategic Responsibilities</h3>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">📈</span>
                                <span class="text-sm text-green-800">Monitor system performance and usage
                                    analytics</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">🔗</span>
                                <span class="text-sm text-green-800">Manage strategic partnerships and
                                    relationships</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">👔</span>
                                <span class="text-sm text-green-800">Handle VIP and courtesy guest arrangements</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">📋</span>
                                <span class="text-sm text-green-800">Review operational reports and insights</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courtesy Guest Management Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                Courtesy Guest Management
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎭 VIP & Courtesy Guests</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Guest Registration</p>
                                <p class="text-gray-500 text-sm">Register VIP guests without host requirements</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Visit Scheduling</p>
                                <p class="text-gray-500 text-sm">Set up special visit arrangements and dates</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Status Monitoring</p>
                                <p class="text-gray-500 text-sm">Track visit approvals and guest notifications</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">4</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Exception Handling</p>
                                <p class="text-gray-500 text-sm">Override limits for special circumstances</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Quick Actions</h3>
                    <div class="space-y-4">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h5 class="font-medium text-yellow-800 mb-2">Priority Guest Protocol</h5>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-yellow-700">Emergency Override</span>
                                    <span
                                        class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Available</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-yellow-700">Limit Bypass</span>
                                    <span
                                        class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Authorized</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-yellow-700">Instant Approval</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Enabled</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics & Reporting Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                Analytics & System Reporting
            </h2>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📈</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-center">Usage Analytics</h3>
                    <p class="text-sm text-gray-600 text-center">
                        Comprehensive visitor patterns, peak times, and system utilization metrics
                    </p>
                    <ul class="text-xs text-gray-500 mt-3 space-y-1">
                        <li>• Daily/weekly/monthly visit trends</li>
                        <li>• Guest nationality demographics</li>
                        <li>• Host activity patterns</li>
                        <li>• System performance indicators</li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📋</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-center">Operational Reports</h3>
                    <p class="text-sm text-gray-600 text-center">
                        Detailed operational insights and business intelligence dashboards
                    </p>
                    <ul class="text-xs text-gray-500 mt-3 space-y-1">
                        <li>• Member engagement metrics</li>
                        <li>• Visit approval rates</li>
                        <li>• System uptime and reliability</li>
                        <li>• Financial impact analysis</li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-center">Strategic Insights</h3>
                    <p class="text-sm text-gray-600 text-center">
                        Strategic business insights and decision-making support data
                    </p>
                    <ul class="text-xs text-gray-500 mt-3 space-y-1">
                        <li>• Partnership performance</li>
                        <li>• Market trend analysis</li>
                        <li>• Revenue optimization</li>
                        <li>• Future planning data</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Advanced Reporting Features</h3>
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="text-indigo-600 mr-3">🔍</span>
                        <div>
                            <p class="text-sm text-indigo-800 font-medium">Custom Report Generation</p>
                            <p class="text-sm text-indigo-700 mt-1">
                                Create tailored reports with advanced filtering, date ranges, and export capabilities.
                                Schedule automated report delivery and set up custom dashboards for key metrics.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Partner & Supplier Management Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                Partner & Supplier Management
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🤝 Supplier Relations</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">1</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Supplier Database</p>
                                <p class="text-gray-500 text-sm">Maintain comprehensive supplier contact information</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">2</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Access Records</p>
                                <p class="text-gray-500 text-sm">Track supplier visits and service history</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">3</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Performance Monitoring</p>
                                <p class="text-gray-500 text-sm">Review supplier service quality and reliability</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏨 Accommodation Partners</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">A</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Guest Management</p>
                                <p class="text-gray-500 text-sm">Oversee accommodation guest registrations</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">B</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Partner Coordination</p>
                                <p class="text-gray-500 text-sm">Manage relationships with accommodation providers</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-semibold mr-3 mt-0.5">C</span>
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Quality Assurance</p>
                                <p class="text-gray-500 text-sm">Monitor accommodation service standards</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔄 Reciprocation Management</h3>
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="text-teal-600 mr-3">🌐</span>
                        <div>
                            <p class="text-sm text-teal-800 font-medium">Partner Club Relations</p>
                            <p class="text-sm text-teal-700 mt-1">
                                Manage reciprocal access arrangements with partner clubs. Track member visits, maintain
                                relationship databases, and coordinate mutual benefits programs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strategic Planning Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <span
                    class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                Strategic Planning & Insights
            </h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Business Intelligence</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">📊</span>
                            <span class="text-sm text-gray-600">Long-term usage trends and forecasting</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">💰</span>
                            <span class="text-sm text-gray-600">Revenue impact analysis and optimization</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">👥</span>
                            <span class="text-sm text-gray-600">Member engagement and retention insights</span>
                        </div>
                        <div class="flex items-start">
                            <span
                                class="flex-shrink-0 w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">📈</span>
                            <span class="text-sm text-gray-600">Performance metrics and KPI tracking</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Strategic Initiatives</h3>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="text-red-500 mr-2">🎪</span>
                                <span class="text-sm text-red-800">Event planning and special occasion management</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-red-500 mr-2">🤝</span>
                                <span class="text-sm text-red-800">Partnership development and networking</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-red-500 mr-2">💡</span>
                                <span class="text-sm text-red-800">Process improvement and system optimization</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-red-500 mr-2">📋</span>
                                <span class="text-sm text-red-800">Policy development and governance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support & Resources Section -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">🆘 General Manager Resources</h2>
                <p class="text-gray-600 mb-6">
                    Access advanced tools and strategic resources for high-level management and decision-making.
                </p>
                <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">📊</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Executive Dashboards</h3>
                        <p class="text-sm text-gray-600">Real-time business intelligence and KPI monitoring tools</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">🤝</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Partner Portal</h3>
                        <p class="text-sm text-gray-600">Advanced supplier and accommodation partner management</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-xl">🎯</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Strategic Planning</h3>
                        <p class="text-sm text-gray-600">Tools for forecasting, planning, and business development</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>