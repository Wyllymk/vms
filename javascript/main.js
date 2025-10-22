import { initAccommodation } from './parts/vms-accommodation.js';
import { initClub } from './parts/vms-club.js';
import { initEmployee } from './parts/vms-employee.js';
import { initGuest } from './parts/vms-guest.js';
import { initMember } from './parts/vms-member.js';
import { initPassword } from './parts/vms-password.js';
import { initProfile } from './parts/vms-profile.js';
import { initReciprocation } from './parts/vms-reciprocation.js';
import { initSettings } from './parts/vms-settings.js';
import { initSupplier } from './parts/vms-suppliers.js';

jQuery(document).ready(function () {
	initAccommodation();
	initClub();
	initEmployee();
	initGuest();
	initMember();
	initPassword();
	initProfile();
	initReciprocation();
	initSettings();
	initSupplier();
});
