<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Amis School Management System (ASMS)</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Google Fonts: Inter & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            mono: ['JetBrains Mono', 'monospace'],
          },
          colors: {
            brand: {
              50: '#ecfdf5',
              100: '#d1fae5',
              200: '#a7f3d0',
              300: '#6ee7b7',
              400: '#34d399',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
              800: '#065f46',
              900: '#064e3b',
            }
          }
        }
      }
    };
  </script>

  <style>
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 9999px;
    }
    .dark ::-webkit-scrollbar-thumb {
      background: #334155;
    }

    /* Print Styles for Report Cards, Receipts & ID Cards */
    @media print {
      body * {
        visibility: hidden !important;
      }
      #printable-area, #printable-area * {
        visibility: visible !important;
      }
      #printable-area {
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        background: white !important;
        color: black !important;
        padding: 20px !important;
        box-shadow: none !important;
        z-index: 99999 !important;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 font-sans antialiased transition-colors duration-200 selection:bg-brand-500 selection:text-white">

  <!-- Toast Notification Container -->
  <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none px-4 sm:px-0"></div>

  <!-- Main App Shell -->
  <div class="flex h-screen overflow-hidden">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity"></div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 bg-slate-900 text-slate-200 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:inset-auto border-r border-slate-800">
      <!-- Brand Header -->
      <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800 bg-slate-950/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-teal-700 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-brand-500/20">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>
          <div>
            <span class="font-bold text-white tracking-tight text-base block leading-none">AMIS SCHOOL</span>
            <span class="text-[11px] font-medium text-brand-400 tracking-wider uppercase mt-1 block">Management System</span>
          </div>
        </div>
        <button onclick="toggleMobileSidebar()" class="lg:hidden text-slate-400 hover:text-white p-2 rounded-lg">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <!-- Active Role Badge in Sidebar -->
      <div class="px-4 py-3 border-b border-slate-800/80 bg-slate-900/60">
        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-800/70 border border-slate-700/60">
          <div id="sidebar-role-avatar" class="w-9 h-9 rounded-lg bg-brand-600/20 text-brand-400 border border-brand-500/30 flex items-center justify-center font-bold text-sm">
            SA
          </div>
          <div class="min-w-0 flex-1">
            <p id="sidebar-user-name" class="text-xs font-semibold text-white truncate">Dr. Cabdiraxmaan Nuur</p>
            <p id="sidebar-role-label" class="text-[11px] text-brand-400 font-medium truncate">Super Admin / Principal</p>
          </div>
        </div>
      </div>

      <!-- Navigation Links (Dynamically populated via RBAC) -->
      <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        <!-- Populated by JS -->
      </nav>

      <!-- Sidebar Footer -->
      <div class="p-4 border-t border-slate-800 bg-slate-950/40 text-xs text-slate-400">
        <div class="flex items-center justify-between mb-1.5">
          <span>System Status</span>
          <span class="flex items-center gap-1.5 text-brand-400 font-medium">
            <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span> Online
          </span>
        </div>
        <p class="text-[11px] text-slate-500">ASMS v2.6 • Security & Audit Active</p>
      </div>
    </aside>

    <!-- Main Content Column -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

      <!-- Top Header Navbar -->
      <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 lg:px-6 z-20 shrink-0">
        <div class="flex items-center gap-3">
          <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fa-solid fa-bars text-lg"></i>
          </button>
          <div>
            <h1 id="page-title" class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Executive Dashboard</h1>
            <p id="page-subtitle" class="text-xs text-slate-500 dark:text-slate-400 hidden sm:block">Overview of Amis School academic & financial operations</p>
          </div>
        </div>

        <!-- Right Controls: RBAC Switcher, Academic Year, Dark Mode -->
        <div class="flex items-center gap-2 sm:gap-3">

          <!-- Role-Based Access Control (RBAC) Live Switcher -->
          <div class="flex items-center bg-brand-50 dark:bg-brand-950/50 border border-brand-200 dark:border-brand-800/70 rounded-xl px-2.5 py-1.5">
            <i class="fa-solid fa-user-shield text-brand-600 dark:text-brand-400 text-xs mr-2 hidden sm:inline"></i>
            <label for="role-switcher" class="sr-only">Switch Role</label>
            <select id="role-switcher" onchange="switchRole(this.value)" class="bg-transparent text-xs sm:text-sm font-semibold text-brand-800 dark:text-brand-300 focus:outline-none cursor-pointer pr-1">
              <option value="admin" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white">Role: Super Admin / Principal</option>
              <option value="accountant" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white">Role: School Accountant</option>
              <option value="teacher" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white">Role: Teacher (Ustaad Cali)</option>
              <option value="student_parent" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white">Role: Student / Parent</option>
            </select>
          </div>

          <!-- Academic Year Switcher -->
          <div class="hidden md:flex items-center bg-slate-100 dark:bg-slate-800 rounded-xl px-3 py-1.5 border border-slate-200 dark:border-slate-700">
            <i class="fa-regular fa-calendar text-slate-500 dark:text-slate-400 text-xs mr-2"></i>
            <select id="academic-year" onchange="changeAcademicYear(this.value)" class="bg-transparent text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none cursor-pointer">
              <option value="2026-2027" selected>AY 2026–2027</option>
              <option value="2025-2026">AY 2025–2026</option>
            </select>
          </div>

          <!-- Dark/Light Theme Toggle -->
          <button onclick="toggleTheme()" title="Toggle Dark/Light Mode" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 transition">
            <i id="theme-icon" class="fa-solid fa-moon text-sm"></i>
          </button>
        </div>
      </header>

      <!-- Dynamic Workspace Viewport -->
      <main id="app-content" class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Rendered dynamically by JS -->
      </main>
    </div>
  </div>

  <!-- ===================================================================== -->
  <!-- MODALS (Admit Student, Record Payment, Report Card, Receipt, ID Card) -->
  <!-- ===================================================================== -->

  <!-- Generic Modal Backdrop & Container -->
  <div id="modal-overlay" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <div id="modal-box" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all my-8">
      <!-- Modal content injected here -->
    </div>
  </div>

  <!-- ===================================================================== -->
  <!-- JAVASCRIPT APPLICATION LOGIC & SEED DATA                              -->
  <!-- ===================================================================== -->
  <script>
    // =========================================================================
    // 1. STATE & REALISTIC AMIS SCHOOL SEED DATA
    // =========================================================================
    const state = {
      currentRole: 'admin', // 'admin' | 'accountant' | 'teacher' | 'student_parent'
      currentTab: 'dashboard',
      academicYear: '2026-2027',
      darkMode: false,

      // Role Definitions & Permissions
      roles: {
        admin: {
          name: 'Dr. Cabdiraxmaan Nuur',
          title: 'Super Admin / Principal',
          avatar: 'CN',
          modules: ['dashboard', 'students', 'academics', 'attendance', 'exams', 'finance', 'hr_library', 'blueprint']
        },
        accountant: {
          name: 'Sahra Maxamuud Xasan',
          title: 'School Accountant / Finance',
          avatar: 'SM',
          modules: ['dashboard', 'finance', 'students', 'blueprint']
        },
        teacher: {
          name: 'Ustaad Cali Warsame',
          title: 'Senior Science Teacher',
          avatar: 'CW',
          modules: ['dashboard', 'attendance', 'exams', 'academics']
        },
        student_parent: {
          name: 'Hodan Cabdi & Waalid',
          title: 'Student (Grade 11-A) / Parent',
          avatar: 'HC',
          modules: ['dashboard', 'exams', 'finance', 'academics']
        }
      },

      // Navigation Items Registry
      navItems: [
        { id: 'dashboard', label: 'Dashboard & Analytics', icon: 'fa-chart-pie' },
        { id: 'students', label: 'Student Information (SIS)', icon: 'fa-user-graduate' },
        { id: 'academics', label: 'Academics & Timetable', icon: 'fa-book-open' },
        { id: 'attendance', label: 'Attendance Tracking', icon: 'fa-clipboard-user' },
        { id: 'exams', label: 'Exams & Report Cards', icon: 'fa-award' },
        { id: 'finance', label: 'Finance & Fee Collection', icon: 'fa-wallet' },
        { id: 'hr_library', label: 'HR, Notices & Library', icon: 'fa-building-columns' },
        { id: 'blueprint', label: 'System Blueprint & SQL', icon: 'fa-database' },
      ],

      // Students Seed Data
      students: [
        { id: 'AMIS-2026-001', name: 'Hodan Cabdi Maxamed', gender: 'Female', class: 'Grade 11', section: 'A', parent: 'Cabdi Maxamed Yuusuf', phone: '+252 63 4419021', status: 'Active', dob: '2009-04-12', blood: 'O+', attendanceRate: 97 },
        { id: 'AMIS-2026-002', name: 'Axmed Maxamed Faarax', gender: 'Male', class: 'Grade 11', section: 'A', parent: 'Maxamed Faarax Geelle', phone: '+252 63 4128830', status: 'Active', dob: '2008-11-23', blood: 'A+', attendanceRate: 94 },
        { id: 'AMIS-2026-003', name: 'Fartuun Cali Xuseen', gender: 'Female', class: 'Grade 11', section: 'A', parent: 'Cali Xuseen Barre', phone: '+252 61 5592014', status: 'Active', dob: '2009-01-18', blood: 'B+', attendanceRate: 99 },
        { id: 'AMIS-2026-004', name: 'Cabdirisaaq Cumar Diiriye', gender: 'Male', class: 'Grade 11', section: 'A', parent: 'Cumar Diiriye Jaamac', phone: '+252 63 4891204', status: 'Active', dob: '2008-08-05', blood: 'O-', attendanceRate: 88 },
        { id: 'AMIS-2026-005', name: 'Nimco Siciid Cilmi', gender: 'Female', class: 'Grade 10', section: 'B', parent: 'Siciid Cilmi Rooble', phone: '+252 90 7741290', status: 'Active', dob: '2010-02-14', blood: 'AB+', attendanceRate: 96 },
        { id: 'AMIS-2026-006', name: 'Maxamuud Cabdillaahi Aadan', gender: 'Male', class: 'Grade 12', section: 'A', parent: 'Cabdillaahi Aadan Nuur', phone: '+252 63 4009182', status: 'Active', dob: '2007-09-30', blood: 'A-', attendanceRate: 92 },
        { id: 'AMIS-2026-007', name: 'Aamina Xasan Shire', gender: 'Female', class: 'Grade 9', section: 'A', parent: 'Xasan Shire Warsame', phone: '+252 61 5239811', status: 'Active', dob: '2011-06-19', blood: 'B-', attendanceRate: 95 },
        { id: 'AMIS-2026-008', name: 'Khaalid Ibraahim Guuleed', gender: 'Male', class: 'Grade 10', section: 'B', parent: 'Ibraahim Guuleed Samatar', phone: '+252 63 4551928', status: 'Pending Transfer', dob: '2010-03-27', blood: 'O+', attendanceRate: 85 },
      ],

      // Classes & Subjects Data
      classes: [
        { id: 'CLS-09A', grade: 'Grade 9', section: 'A', classTeacher: 'Ustaad Muxumed Cali', room: 'Block B - 101', capacity: 35, enrolled: 32, subjects: ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'Somali', 'Arabic', 'English', 'Islamic Studies'] },
        { id: 'CLS-10B', grade: 'Grade 10', section: 'B', classTeacher: 'Macallin Faadumo Nuur', room: 'Block B - 204', capacity: 35, enrolled: 34, subjects: ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'Somali', 'Arabic', 'English', 'History'] },
        { id: 'CLS-11A', grade: 'Grade 11', section: 'A', classTeacher: 'Ustaad Cali Warsame', room: 'Science Wing - 301', capacity: 35, enrolled: 33, subjects: ['Advanced Math', 'Physics', 'Chemistry', 'Biology', 'English', 'Islamic Studies', 'ICT'] },
        { id: 'CLS-12A', grade: 'Grade 12', section: 'A', classTeacher: 'Dr. Cabdiraxmaan Nuur', room: 'Senior Hall - 401', capacity: 30, enrolled: 29, subjects: ['Calculus', 'Physics', 'Chemistry', 'Biology', 'English', 'Somali Literature', 'Geography'] },
      ],

      // Weekly Timetable Seed Data
      timetable: {
        'Grade 11-A': [
          { day: 'Saturday', p1: 'Physics (Ustaad Cali)', p2: 'Advanced Math (Ustaad Xasan)', p3: 'English (Macallin Maryan)', p4: 'Islamic Studies (Sheekh Yuusuf)' },
          { day: 'Sunday', p1: 'Chemistry (Macallin Sahra)', p2: 'Biology (Ustaad Cabdi)', p3: 'ICT Lab (Eng. Farxaan)', p4: 'Somali (Ustaad Geelle)' },
          { day: 'Monday', p1: 'Advanced Math (Ustaad Xasan)', p2: 'Physics Lab (Ustaad Cali)', p3: 'Arabic (Sheekh Axmed)', p4: 'English (Macallin Maryan)' },
          { day: 'Tuesday', p1: 'Biology (Ustaad Cabdi)', p2: 'Chemistry (Macallin Sahra)', p3: 'Advanced Math (Ustaad Xasan)', p4: 'PE & Sports (Coach Barre)' },
          { day: 'Wednesday', p1: 'Physics (Ustaad Cali)', p2: 'Islamic Studies (Sheekh Yuusuf)', p3: 'Chemistry Lab (Macallin Sahra)', p4: 'Revision & Mentoring' },
        ],
        'Grade 10-B': [
          { day: 'Saturday', p1: 'Mathematics (Ustaad Xasan)', p2: 'English (Macallin Maryan)', p3: 'Biology (Ustaad Cabdi)', p4: 'History (Ustaad Cumar)' },
          { day: 'Sunday', p1: 'Physics (Ustaad Cali)', p2: 'Islamic Studies (Sheekh Yuusuf)', p3: 'Chemistry (Macallin Sahra)', p4: 'Somali (Ustaad Geelle)' },
          { day: 'Monday', p1: 'Arabic (Sheekh Axmed)', p2: 'Mathematics (Ustaad Xasan)', p3: 'English (Macallin Maryan)', p4: 'ICT Lab (Eng. Farxaan)' },
          { day: 'Tuesday', p1: 'Chemistry (Macallin Sahra)', p2: 'Physics (Ustaad Cali)', p3: 'Biology (Ustaad Cabdi)', p4: 'Geography (Macallin Hibo)' },
          { day: 'Wednesday', p1: 'Mathematics (Ustaad Xasan)', p2: 'Somali (Ustaad Geelle)', p3: 'Islamic Studies (Sheekh Yuusuf)', p4: 'Class Assembly' },
        ]
      },

      // Attendance Records (for Grade 11-A today)
      attendanceRecords: {
        'AMIS-2026-001': 'Present',
        'AMIS-2026-002': 'Present',
        'AMIS-2026-003': 'Present',
        'AMIS-2026-004': 'Late',
        'AMIS-2026-005': 'Present',
        'AMIS-2026-006': 'Present',
        'AMIS-2026-007': 'Absent',
        'AMIS-2026-008': 'Excused',
      },

      // Exam Marks Data (Grade 11-A Mid-Term)
      examMarks: [
        { studentId: 'AMIS-2026-001', name: 'Hodan Cabdi Maxamed', math: 96, physics: 94, chemistry: 92, biology: 95, english: 91, islamic: 98 },
        { studentId: 'AMIS-2026-002', name: 'Axmed Maxamed Faarax', math: 85, physics: 88, chemistry: 81, biology: 84, english: 79, islamic: 92 },
        { studentId: 'AMIS-2026-003', name: 'Fartuun Cali Xuseen', math: 91, physics: 89, chemistry: 94, biology: 93, english: 90, islamic: 96 },
        { studentId: 'AMIS-2026-004', name: 'Cabdirisaaq Cumar Diiriye', math: 72, physics: 68, chemistry: 74, biology: 71, english: 76, islamic: 85 },
      ],

      // Finance Invoices Data
      invoices: [
        { id: 'INV-2026-1041', studentId: 'AMIS-2026-001', studentName: 'Hodan Cabdi Maxamed', class: 'Grade 11-A', feeType: 'Term 1 Tuition & Lab Fee', amount: 180, paid: 180, method: 'Zaad', date: '2026-11-02', status: 'Paid' },
        { id: 'INV-2026-1042', studentId: 'AMIS-2026-002', studentName: 'Axmed Maxamed Faarax', class: 'Grade 11-A', feeType: 'Term 1 Tuition & Transport', amount: 210, paid: 150, method: 'EVC Plus', date: '2026-11-05', status: 'Partial' },
        { id: 'INV-2026-1043', studentId: 'AMIS-2026-003', studentName: 'Fartuun Cali Xuseen', class: 'Grade 11-A', feeType: 'Term 1 Tuition Fee', amount: 160, paid: 160, method: 'Sahal', date: '2026-11-01', status: 'Paid' },
        { id: 'INV-2026-1044', studentId: 'AMIS-2026-004', studentName: 'Cabdirisaaq Cumar Diiriye', class: 'Grade 11-A', feeType: 'Term 1 Tuition & Exam Fee', amount: 195, paid: 0, method: 'Pending', date: '2026-10-25', status: 'Overdue' },
        { id: 'INV-2026-1045', studentId: 'AMIS-2026-005', studentName: 'Nimco Siciid Cilmi', class: 'Grade 10-B', feeType: 'Term 1 Tuition Fee', amount: 150, paid: 150, method: 'Bank Transfer', date: '2026-11-08', status: 'Paid' },
        { id: 'INV-2026-1046', studentId: 'AMIS-2026-006', studentName: 'Maxamuud Cabdillaahi Aadan', class: 'Grade 12-A', feeType: 'Senior Exam & Tuition', amount: 220, paid: 120, method: 'Cash', date: '2026-11-10', status: 'Partial' },
      ],

      // Notice Board Announcements
      notices: [
        { id: 1, title: 'Mid-Term Examination Schedule Released', audience: 'All Students & Parents', date: 'Nov 20, 2026', priority: 'High', desc: 'Term 1 Mid-Term exams begin next Saturday at 8:00 AM. Please clear any pending term fee balances to download exam hall passes.' },
        { id: 2, title: 'Parent-Teacher Academic Consultation Day', audience: 'Parents & Teachers', date: 'Nov 18, 2026', priority: 'Normal', desc: 'Parents are invited to meet subject teachers at Amis School Main Auditorium this Thursday from 9:00 AM to 1:00 PM.' },
        { id: 3, title: 'New STEM & Robotics Lab Equipment Arrived', audience: 'Teachers & Grade 10-12', date: 'Nov 14, 2026', priority: 'Info', desc: 'Science faculty can now book Lab Room 302 for practical physics and electronics sessions.' },
      ],

      // Library Books Seed Data
      libraryBooks: [
        { isbn: '978-0134093413', title: 'Campbell Biology (12th Edition)', author: 'Lisa A. Urry', category: 'Science', copies: 18, available: 14 },
        { isbn: '978-0321982384', title: 'University Physics with Modern Physics', author: 'Hugh D. Young', category: 'Physics', copies: 15, available: 11 },
        { isbn: '978-9966251121', title: 'Taariikhda iyo Suugaanta Soomaalida', author: 'Prof. Cabdalla Mansuur', category: 'Literature', copies: 25, available: 22 },
        { isbn: '978-1449355739', title: 'Learning Python & Data Science', author: 'Mark Lutz', category: 'ICT', copies: 12, available: 9 },
      ]
    };

    // Chart instances registry to destroy cleanly on re-render
    let revenueChartInstance = null;
    let attendanceChartInstance = null;

    // =========================================================================
    // 2. INITIALIZATION & NAVIGATION CONTROLLERS
    // =========================================================================
    document.addEventListener('DOMContentLoaded', () => {
      renderSidebar();
      renderMainView();
    });

    function toggleMobileSidebar() {
      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');
      sidebar.classList.toggle('-translate-x-full');
      backdrop.classList.toggle('hidden');
    }

    function toggleTheme() {
      state.darkMode = !state.darkMode;
      const html = document.documentElement;
      const icon = document.getElementById('theme-icon');
      if (state.darkMode) {
        html.classList.add('dark');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
      } else {
        html.classList.remove('dark');
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
      }
      if (state.currentTab === 'dashboard') {
        renderMainView(); // Re-render charts for dark/light contrast
      }
    }

    function changeAcademicYear(year) {
      state.academicYear = year;
      showToast(`Switched active session to Academic Year ${year}`, 'info');
      renderMainView();
    }

    function switchRole(newRole) {
      state.currentRole = newRole;
      const roleConfig = state.roles[newRole];

      // Update Sidebar Profile Card
      document.getElementById('sidebar-role-avatar').textContent = roleConfig.avatar;
      document.getElementById('sidebar-user-name').textContent = roleConfig.name;
      document.getElementById('sidebar-role-label').textContent = roleConfig.title;

      // Ensure currentTab is allowed for the new role; fallback to first permitted module
      if (!roleConfig.modules.includes(state.currentTab)) {
        state.currentTab = roleConfig.modules[0];
      }

      renderSidebar();
      renderMainView();
      showToast(`RBAC Active: Switched to ${roleConfig.title}`, 'success');
    }

    function navigateTo(tabId) {
      state.currentTab = tabId;
      renderSidebar();
      renderMainView();
      // Close mobile sidebar if open
      const sidebar = document.getElementById('sidebar');
      if (!sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
        toggleMobileSidebar();
      }
    }

    function renderSidebar() {
      const navContainer = document.getElementById('sidebar-nav');
      const allowedModules = state.roles[state.currentRole].modules;

      navContainer.innerHTML = state.navItems
        .filter(item => allowedModules.includes(item.id))
        .map(item => {
          const isActive = state.currentTab === item.id;
          return `
            <button onclick="navigateTo('${item.id}')"
              class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all ${
                isActive
                  ? 'bg-brand-600 text-white shadow-md shadow-brand-600/25'
                  : 'text-slate-300 hover:bg-slate-800/80 hover:text-white'
              }">
              <i class="fa-solid ${item.icon} w-5 text-center ${isActive ? 'text-white' : 'text-slate-400'}"></i>
              <span class="truncate">${item.label}</span>
            </button>
          `;
        }).join('');
    }

    // =========================================================================
    // 3. MAIN VIEW ROUTER
    // =========================================================================
    function renderMainView() {
      const container = document.getElementById('app-content');
      const titleEl = document.getElementById('page-title');
      const subtitleEl = document.getElementById('page-subtitle');

      // Destroy existing charts before re-rendering DOM
      if (revenueChartInstance) { revenueChartInstance.destroy(); revenueChartInstance = null; }
      if (attendanceChartInstance) { attendanceChartInstance.destroy(); attendanceChartInstance = null; }

      switch (state.currentTab) {
        case 'dashboard':
          titleEl.textContent = `${state.roles[state.currentRole].title.split('/')[0].trim()} Dashboard`;
          subtitleEl.textContent = `Amis School • Academic Year ${state.academicYear} Real-Time Analytics`;
          container.innerHTML = renderDashboardView();
          if (state.currentRole === 'admin' || state.currentRole === 'accountant') {
            setTimeout(initDashboardCharts, 50);
          }
          break;

        case 'students':
          titleEl.textContent = 'Student Information System (SIS)';
          subtitleEl.textContent = 'Manage student admissions, directory, profiles, and official ID cards';
          container.innerHTML = renderStudentsView();
          break;

        case 'academics':
          titleEl.textContent = 'Academics & Weekly Timetable';
          subtitleEl.textContent = 'Class sections, subject allocations, and conflict-free weekly schedules';
          container.innerHTML = renderAcademicsView();
          break;

        case 'attendance':
          titleEl.textContent = 'Attendance Management';
          subtitleEl.textContent = 'Daily student attendance roll-call with automated parent SMS alerts';
          container.innerHTML = renderAttendanceView();
          break;

        case 'exams':
          titleEl.textContent = 'Examinations & Grading System';
          subtitleEl.textContent = 'Automated GPA calculation, class ranking, and official printable report cards';
          container.innerHTML = renderExamsView();
          break;

        case 'finance':
          titleEl.textContent = 'Finance & Fee Collection';
          subtitleEl.textContent = 'Invoicing, mobile money payments (Zaad/EVC Plus/Sahal), and receipts';
          container.innerHTML = renderFinanceView();
          break;

        case 'hr_library':
          titleEl.textContent = 'HR, Notice Board & Library';
          subtitleEl.textContent = 'Staff directory, school-wide announcements, and library book inventory';
          container.innerHTML = renderHrLibraryView();
          break;

        case 'blueprint':
          titleEl.textContent = 'System Architecture & SQL Blueprint';
          subtitleEl.textContent = 'Production PostgreSQL/MySQL relational schema, RBAC matrix, and tech stack';
          container.innerHTML = renderBlueprintView();
          break;
      }
    }

    // =========================================================================
    // 4. MODULE VIEWS
    // =========================================================================

    // --- A. ROLE-SPECIFIC DASHBOARD VIEW ---
    function renderDashboardView() {
      // 1. TEACHER DASHBOARD
      if (state.currentRole === 'teacher') {
        return `
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Assigned Classes</p>
                  <h3 class="text-2xl font-bold mt-1">4 Classes</h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-brand-500/10 text-brand-600 flex items-center justify-center text-lg">
                  <i class="fa-solid fa-chalkboard-user"></i>
                </div>
              </div>
              <p class="text-xs text-slate-500 mt-3">Grade 10-B, Grade 11-A, Grade 12-A</p>
            </div>

            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Attendance</p>
                  <h3 class="text-2xl font-bold mt-1 text-brand-600">94.8%</h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center text-lg">
                  <i class="fa-solid fa-user-check"></i>
                </div>
              </div>
              <p class="text-xs text-slate-500 mt-3">Grade 11-A Homeroom marked</p>
            </div>

            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pending Marks Entry</p>
                  <h3 class="text-2xl font-bold mt-1 text-amber-600">12 Papers</h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-lg">
                  <i class="fa-solid fa-pen-to-square"></i>
                </div>
              </div>
              <p class="text-xs text-slate-500 mt-3">Physics Mid-Term Term 1</p>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
              <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-base">Today's Teaching Schedule (Ustaad Cali Warsame)</h3>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-brand-100 text-brand-800 dark:bg-brand-900/40 dark:text-brand-300">Physics & Science</span>
              </div>
              <div class="space-y-3">
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                  <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-lg bg-brand-600 text-white text-xs font-bold">08:00 AM</span>
                    <div>
                      <p class="font-semibold text-sm">Physics — Mechanics & Kinematics</p>
                      <p class="text-xs text-slate-500">Grade 11 - Section A • Science Wing 301</p>
                    </div>
                  </div>
                  <button onclick="navigateTo('attendance')" class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-semibold hover:border-brand-500 transition">Take Attendance</button>
                </div>

                <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                  <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-700 text-white text-xs font-bold">10:15 AM</span>
                    <div>
                      <p class="font-semibold text-sm">Electromagnetism Lab Practical</p>
                      <p class="text-xs text-slate-500">Grade 12 - Section A • Physics Lab 302</p>
                    </div>
                  </div>
                  <button onclick="navigateTo('exams')" class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-semibold hover:border-brand-500 transition">Enter Marks</button>
                </div>

                <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                  <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-700 text-white text-xs font-bold">11:45 AM</span>
                    <div>
                      <p class="font-semibold text-sm">General Physics — Waves & Optics</p>
                      <p class="text-xs text-slate-500">Grade 10 - Section B • Block B 204</p>
                    </div>
                  </div>
                  <button onclick="navigateTo('academics')" class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-semibold hover:border-brand-500 transition">View Routine</button>
                </div>
              </div>
            </div>

            ${renderNoticeWidget()}
          </div>
        `;
      }

      // 2. STUDENT / PARENT DASHBOARD
      if (state.currentRole === 'student_parent') {
        const student = state.students[0]; // Hodan Cabdi Maxamed
        return `
          <div class="bg-gradient-to-r from-brand-700 to-teal-800 rounded-2xl p-6 text-white shadow-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center text-2xl font-bold border border-white/25">
                HC
              </div>
              <div>
                <span class="text-xs uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full font-semibold">Student & Guardian Portal</span>
                <h2 class="text-xl sm:text-2xl font-bold mt-1">${student.name}</h2>
                <p class="text-sm text-brand-100">ID: ${student.id} • ${student.class} (Section ${student.section}) • Guardian: ${student.parent}</p>
              </div>
            </div>
            <button onclick="openReportCardModal('AMIS-2026-001')" class="px-4 py-2.5 rounded-xl bg-white text-brand-800 font-semibold text-sm hover:bg-brand-50 transition shadow">
              <i class="fa-solid fa-file-lines mr-1.5"></i> View Official Report Card
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
              <p class="text-xs font-semibold text-slate-500 uppercase">Attendance Rate</p>
              <h3 class="text-2xl font-bold text-brand-600 mt-1">${student.attendanceRate}%</h3>
              <p class="text-xs text-slate-500 mt-2">Present 64 of 66 school days</p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
              <p class="text-xs font-semibold text-slate-500 uppercase">Mid-Term Average & Rank</p>
              <h3 class="text-2xl font-bold text-blue-600 mt-1">94.3% (A+)</h3>
              <p class="text-xs text-slate-500 mt-2">Class Rank: 1st out of 33 Students</p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
              <p class="text-xs font-semibold text-slate-500 uppercase">Fee Balance Status</p>
              <h3 class="text-2xl font-bold text-emerald-600 mt-1">$0.00 Due</h3>
              <p class="text-xs text-slate-500 mt-2">Term 1 Tuition Paid via Zaad</p>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            ${renderNoticeWidget()}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
              <h3 class="font-bold text-base mb-4">Upcoming Exams & Assignments</h3>
              <div class="space-y-3 text-sm">
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 flex justify-between items-center">
                  <div>
                    <p class="font-semibold">Advanced Mathematics — Calculus Quiz</p>
                    <p class="text-xs text-slate-500">Ustaad Xasan • Room 301</p>
                  </div>
                  <span class="text-xs font-bold text-amber-600 bg-amber-500/10 px-2.5 py-1 rounded-lg">Nov 26, 2026</span>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 flex justify-between items-center">
                  <div>
                    <p class="font-semibold">Physics Lab Report Submission</p>
                    <p class="text-xs text-slate-500">Ustaad Cali Warsame</p>
                  </div>
                  <span class="text-xs font-bold text-blue-600 bg-blue-500/10 px-2.5 py-1 rounded-lg">Nov 28, 2026</span>
                </div>
              </div>
            </div>
          </div>
        `;
      }

      // 3. ADMIN / PRINCIPAL & ACCOUNTANT EXECUTIVE DASHBOARD
      const totalCollected = state.invoices.reduce((sum, inv) => sum + inv.paid, 0);
      const totalBilled = state.invoices.reduce((sum, inv) => sum + inv.amount, 0);

      return `
        <!-- Top KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
          <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500/50 transition">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Enrolled Students</p>
                <h3 class="text-2xl font-extrabold mt-1 text-slate-900 dark:text-white">${(1232 + state.students.length).toLocaleString()}</h3>
              </div>
              <div class="w-12 h-12 rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-graduate"></i>
              </div>
            </div>
            <div class="mt-3 flex items-center text-xs">
              <span class="text-emerald-600 font-semibold flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> +6.4%</span>
              <span class="text-slate-400 ml-2">vs last academic year</span>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-blue-500/50 transition">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Faculty & Staff</p>
                <h3 class="text-2xl font-extrabold mt-1 text-slate-900 dark:text-white">68</h3>
              </div>
              <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-chalkboard-user"></i>
              </div>
            </div>
            <div class="mt-3 flex items-center text-xs">
              <span class="text-blue-600 font-semibold">54 Teachers</span>
              <span class="text-slate-400 ml-2">• 14 Admin & Support</span>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-purple-500/50 transition">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Active Classes & Sections</p>
                <h3 class="text-2xl font-extrabold mt-1 text-slate-900 dark:text-white">36</h3>
              </div>
              <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-school"></i>
              </div>
            </div>
            <div class="mt-3 flex items-center text-xs">
              <span class="text-purple-600 font-semibold">Grade 1 – Grade 12</span>
              <span class="text-slate-400 ml-2">• Sections A, B, C</span>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-amber-500/50 transition">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Monthly Fee Revenue</p>
                <h3 class="text-2xl font-extrabold mt-1 text-slate-900 dark:text-white">$${(42090 + totalCollected).toLocaleString()}</h3>
              </div>
              <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-sack-dollar"></i>
              </div>
            </div>
            <div class="mt-3 flex items-center text-xs">
              <span class="text-emerald-600 font-semibold">${Math.round((totalCollected / totalBilled) * 100)}% Collection Rate</span>
              <span class="text-slate-400 ml-2">this term</span>
            </div>
          </div>
        </div>

        <!-- Analytics Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
              <div>
                <h3 class="font-bold text-base text-slate-900 dark:text-white">Monthly Fee Revenue vs. Operational Expenses</h3>
                <p class="text-xs text-slate-500">Financial performance in USD ($) across Term 1</p>
              </div>
              <button onclick="navigateTo('finance')" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Open Finance Ledger &rarr;</button>
            </div>
            <div class="h-64 w-full">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col">
            <div class="mb-4">
              <h3 class="font-bold text-base text-slate-900 dark:text-white">Daily Attendance Overview</h3>
              <p class="text-xs text-slate-500">School-wide student attendance today</p>
            </div>
            <div class="h-52 w-full flex items-center justify-center">
              <canvas id="attendanceChart"></canvas>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-between text-xs text-slate-500">
              <span>Present: <strong class="text-emerald-600">93.5%</strong></span>
              <span>Absent: <strong class="text-rose-500">3.8%</strong></span>
              <span>Late: <strong class="text-amber-500">2.7%</strong></span>
            </div>
          </div>
        </div>

        <!-- Bottom Row: Notice Board & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2">
            ${renderNoticeWidget()}
          </div>

          <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="font-bold text-base mb-4">Quick Administrative Actions</h3>
            <div class="grid grid-cols-2 gap-3">
              <button onclick="openAdmitStudentModal()" class="p-3.5 rounded-xl bg-brand-50 dark:bg-brand-950/50 border border-brand-200 dark:border-brand-800 text-left hover:bg-brand-100 dark:hover:bg-brand-900/50 transition">
                <i class="fa-solid fa-user-plus text-brand-600 text-lg mb-2 block"></i>
                <span class="text-xs font-bold block">Admit Student</span>
                <span class="text-[11px] text-slate-500">Auto ID & Profile</span>
              </button>
              <button onclick="navigateTo('finance')" class="p-3.5 rounded-xl bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 text-left hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                <i class="fa-solid fa-receipt text-blue-600 text-lg mb-2 block"></i>
                <span class="text-xs font-bold block">Collect Fees</span>
                <span class="text-[11px] text-slate-500">Zaad / EVC / Cash</span>
              </button>
              <button onclick="navigateTo('exams')" class="p-3.5 rounded-xl bg-purple-50 dark:bg-purple-950/50 border border-purple-200 dark:border-purple-800 text-left hover:bg-purple-100 dark:hover:bg-purple-900/50 transition">
                <i class="fa-solid fa-award text-purple-600 text-lg mb-2 block"></i>
                <span class="text-xs font-bold block">Report Cards</span>
                <span class="text-[11px] text-slate-500">Print Transcripts</span>
              </button>
              <button onclick="navigateTo('blueprint')" class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-left hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-database text-slate-600 dark:text-slate-300 text-lg mb-2 block"></i>
                <span class="text-xs font-bold block">SQL Schema</span>
                <span class="text-[11px] text-slate-500">ERD & Architecture</span>
              </button>
            </div>
          </div>
        </div>
      `;
    }

    function renderNoticeWidget() {
      return `
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-base flex items-center gap-2">
              <i class="fa-solid fa-bullhorn text-brand-600"></i> Amis School Notice Board
            </h3>
            <span class="text-xs text-slate-400">Latest Updates</span>
          </div>
          <div class="space-y-3">
            ${state.notices.map(n => `
              <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                  <span class="font-semibold text-sm text-slate-900 dark:text-white">${n.title}</span>
                  <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full ${
                    n.priority === 'High' ? 'bg-rose-500/10 text-rose-600' : 'bg-brand-500/10 text-brand-600'
                  }">${n.date}</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">${n.desc}</p>
              </div>
            `).join('')}
          </div>
        </div>
      `;
    }

    function initDashboardCharts() {
      const textColor = state.darkMode ? '#94a3b8' : '#64748b';
      const gridColor = state.darkMode ? '#1e293b' : '#f1f5f9';

      const revCanvas = document.getElementById('revenueChart');
      if (revCanvas) {
        revenueChartInstance = new Chart(revCanvas, {
          type: 'bar',
          data: {
            labels: ['July', 'August', 'September', 'October', 'November'],
            datasets: [
              {
                label: 'Fee Revenue ($)',
                data: [34200, 38900, 44500, 41200, 42850],
                backgroundColor: '#10b981',
                borderRadius: 6,
              },
              {
                label: 'Payroll & Expenses ($)',
                data: [24100, 25300, 27800, 26400, 26900],
                backgroundColor: '#64748b',
                borderRadius: 6,
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { labels: { color: textColor, font: { family: 'Inter', size: 12 } } }
            },
            scales: {
              x: { ticks: { color: textColor }, grid: { display: false } },
              y: { ticks: { color: textColor }, grid: { color: gridColor } }
            }
          }
        });
      }

      const attCanvas = document.getElementById('attendanceChart');
      if (attCanvas) {
        attendanceChartInstance = new Chart(attCanvas, {
          type: 'doughnut',
          data: {
            labels: ['Present', 'Absent', 'Late / Excused'],
            datasets: [{
              data: [1160, 47, 33],
              backgroundColor: ['#10b981', '#f43f5e', '#f59e0b'],
              borderWidth: 0,
              hoverOffset: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
              legend: { position: 'bottom', labels: { color: textColor, font: { family: 'Inter', size: 11 } } }
            }
          }
        });
      }
    }

    // --- B. STUDENT INFORMATION SYSTEM (SIS) ---
    function renderStudentsView() {
      return `
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
          <!-- Toolbar -->
          <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 flex-1">
              <div class="relative flex-1 min-w-[220px] max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input id="student-search" oninput="filterStudentsTable()" type="text" placeholder="Search by name, ID (AMIS-2026)..."
                  class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-brand-500">
              </div>

              <select id="student-class-filter" onchange="filterStudentsTable()" class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none">
                <option value="ALL">All Classes</option>
                <option value="Grade 9">Grade 9</option>
                <option value="Grade 10">Grade 10</option>
                <option value="Grade 11">Grade 11</option>
                <option value="Grade 12">Grade 12</option>
              </select>

              <select id="student-gender-filter" onchange="filterStudentsTable()" class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none">
                <option value="ALL">All Genders</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
              </select>
            </div>

            <div class="flex items-center gap-2.5">
              <button onclick="exportToCSV('students')" class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                <i class="fa-solid fa-file-csv mr-1.5 text-emerald-600"></i> Export CSV
              </button>
              <button onclick="openAdmitStudentModal()" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Admit New Student
              </button>
            </div>
          </div>

          <!-- Students Table -->
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                  <th class="py-3.5 px-5 font-semibold">Student ID</th>
                  <th class="py-3.5 px-5 font-semibold">Full Name</th>
                  <th class="py-3.5 px-5 font-semibold">Class & Sec</th>
                  <th class="py-3.5 px-5 font-semibold">Parent / Guardian</th>
                  <th class="py-3.5 px-5 font-semibold">Attendance</th>
                  <th class="py-3.5 px-5 font-semibold">Status</th>
                  <th class="py-3.5 px-5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="students-tbody" class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                ${renderStudentRows(state.students)}
              </tbody>
            </table>
          </div>
        </div>
      `;
    }

    function renderStudentRows(list) {
      if (list.length === 0) {
        return `<tr><td colspan="7" class="py-8 text-center text-slate-400 text-sm">No students matched your search filter.</td></tr>`;
      }
      return list.map(s => `
        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
          <td class="py-3.5 px-5 font-mono text-xs font-semibold text-brand-600 dark:text-brand-400">${s.id}</td>
          <td class="py-3.5 px-5">
            <div class="font-semibold text-slate-900 dark:text-white">${s.name}</div>
            <div class="text-xs text-slate-400">${s.gender} • Blood: ${s.blood}</div>
          </td>
          <td class="py-3.5 px-5">
            <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-xs font-semibold">${s.class} - ${s.section}</span>
          </td>
          <td class="py-3.5 px-5">
            <div class="text-xs font-medium">${s.parent}</div>
            <div class="text-[11px] text-slate-400">${s.phone}</div>
          </td>
          <td class="py-3.5 px-5">
            <div class="flex items-center gap-2">
              <div class="w-16 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-brand-500 rounded-full" style="width: ${s.attendanceRate}%"></div>
              </div>
              <span class="text-xs font-semibold">${s.attendanceRate}%</span>
            </div>
          </td>
          <td class="py-3.5 px-5">
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold ${
              s.status === 'Active'
                ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                : 'bg-amber-500/10 text-amber-600'
            }">${s.status}</span>
          </td>
          <td class="py-3.5 px-5 text-right space-x-1">
            <button onclick="openStudentIdModal('${s.id}')" title="Print Student ID Card" class="px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-brand-600 hover:text-white text-xs font-medium transition">
              <i class="fa-solid fa-id-card mr-1"></i> ID Card
            </button>
          </td>
        </tr>
      `).join('');
    }

    function filterStudentsTable() {
      const q = document.getElementById('student-search').value.toLowerCase();
      const cls = document.getElementById('student-class-filter').value;
      const gen = document.getElementById('student-gender-filter').value;

      const filtered = state.students.filter(s => {
        const matchQuery = s.name.toLowerCase().includes(q) || s.id.toLowerCase().includes(q) || s.parent.toLowerCase().includes(q);
        const matchClass = cls === 'ALL' || s.class === cls;
        const matchGender = gen === 'ALL' || s.gender === gen;
        return matchQuery && matchClass && matchGender;
      });

      document.getElementById('students-tbody').innerHTML = renderStudentRows(filtered);
    }

    // --- C. ACADEMICS & TIMETABLE ---
    function renderAcademicsView() {
      return `
        <!-- Classes Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
          ${state.classes.map(c => `
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <div class="flex items-center justify-between mb-2">
                <span class="px-2.5 py-1 rounded-lg bg-brand-500/10 text-brand-600 dark:text-brand-400 text-xs font-bold">${c.grade} — Sec ${c.section}</span>
                <span class="text-xs text-slate-400">${c.room}</span>
              </div>
              <h4 class="font-bold text-sm mt-2">Class Teacher: ${c.classTeacher}</h4>
              <p class="text-xs text-slate-500 mt-1">Enrollment: <strong>${c.enrolled}/${c.capacity}</strong> Students</p>
              <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-1">
                ${c.subjects.slice(0, 4).map(sub => `<span class="text-[10px] px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">${sub}</span>`).join('')}
                <span class="text-[10px] px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-400">+${c.subjects.length - 4} more</span>
              </div>
            </div>
          `).join('')}
        </div>

        <!-- Weekly Class Timetable -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div>
              <h3 class="font-bold text-base">Interactive Weekly Class Timetable</h3>
              <p class="text-xs text-slate-500">Conflict-checked teacher & classroom schedule (Saturday – Wednesday)</p>
            </div>
            <div class="flex items-center gap-3">
              <select id="timetable-class-select" onchange="updateTimetableGrid(this.value)" class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold focus:outline-none">
                <option value="Grade 11-A">Grade 11 - Section A</option>
                <option value="Grade 10-B">Grade 10 - Section B</option>
              </select>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 text-xs uppercase border-b border-slate-200 dark:border-slate-800">
                  <th class="py-3.5 px-5 font-semibold">Day</th>
                  <th class="py-3.5 px-5 font-semibold">Period 1 (08:00–09:00)</th>
                  <th class="py-3.5 px-5 font-semibold">Period 2 (09:05–10:05)</th>
                  <th class="py-3.5 px-5 font-semibold bg-amber-500/5 text-amber-600">Break (10:05–10:30)</th>
                  <th class="py-3.5 px-5 font-semibold">Period 3 (10:30–11:30)</th>
                  <th class="py-3.5 px-5 font-semibold">Period 4 (11:35–12:35)</th>
                </tr>
              </thead>
              <tbody id="timetable-tbody" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs sm:text-sm">
                ${renderTimetableRows('Grade 11-A')}
              </tbody>
            </table>
          </div>
        </div>
      `;
    }

    function renderTimetableRows(classKey) {
      const rows = state.timetable[classKey] || state.timetable['Grade 11-A'];
      return rows.map(r => `
        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
          <td class="py-3.5 px-5 font-bold text-brand-600 dark:text-brand-400">${r.day}</td>
          <td class="py-3.5 px-5 font-medium">${r.p1}</td>
          <td class="py-3.5 px-5 font-medium">${r.p2}</td>
          <td class="py-3.5 px-5 text-center text-[11px] font-semibold text-amber-600 bg-amber-500/5">Morning Break</td>
          <td class="py-3.5 px-5 font-medium">${r.p3}</td>
          <td class="py-3.5 px-5 font-medium">${r.p4}</td>
        </tr>
      `).join('');
    }

    function updateTimetableGrid(classKey) {
      document.getElementById('timetable-tbody').innerHTML = renderTimetableRows(classKey);
    }

    // --- D. ATTENDANCE MANAGEMENT ---
    function renderAttendanceView() {
      const counts = { Present: 0, Absent: 0, Late: 0, Excused: 0 };
      Object.values(state.attendanceRecords).forEach(status => { if (counts[status] !== undefined) counts[status]++; });

      return `
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
              <div>
                <label class="text-[11px] font-semibold uppercase text-slate-400 block mb-1">Class & Section</label>
                <select class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold">
                  <option>Grade 11 - Section A</option>
                  <option>Grade 10 - Section B</option>
                  <option>Grade 12 - Section A</option>
                </select>
              </div>
              <div>
                <label class="text-[11px] font-semibold uppercase text-slate-400 block mb-1">Attendance Date</label>
                <input type="date" value="2026-11-23" class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold">
              </div>
            </div>

            <!-- Real-time Counters & Actions -->
            <div class="flex flex-wrap items-center gap-3">
              <div id="attendance-summary-badges" class="flex items-center gap-2 text-xs font-bold">
                <span class="px-2.5 py-1.5 rounded-lg bg-emerald-500/10 text-emerald-600">Present: ${counts.Present}</span>
                <span class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 text-rose-600">Absent: ${counts.Absent}</span>
                <span class="px-2.5 py-1.5 rounded-lg bg-amber-500/10 text-amber-600">Late: ${counts.Late}</span>
              </div>
              <button onclick="markAllPresent()" class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                Mark All Present
              </button>
              <button onclick="saveAttendanceAndNotify()" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-paper-plane mr-1.5"></i> Save & Notify Parents
              </button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 text-[11px] uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                  <th class="py-3.5 px-5 font-semibold">Student ID</th>
                  <th class="py-3.5 px-5 font-semibold">Student Name</th>
                  <th class="py-3.5 px-5 font-semibold">Guardian SMS Contact</th>
                  <th class="py-3.5 px-5 font-semibold text-right">Attendance Status</th>
                </tr>
              </thead>
              <tbody id="attendance-tbody" class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                ${renderAttendanceRows()}
              </tbody>
            </table>
          </div>
        </div>
      `;
    }

    function renderAttendanceRows() {
      const statuses = ['Present', 'Absent', 'Late', 'Excused'];
      return state.students.map(s => {
        const current = state.attendanceRecords[s.id] || 'Present';
        return `
          <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
            <td class="py-3.5 px-5 font-mono text-xs font-semibold text-brand-600">${s.id}</td>
            <td class="py-3.5 px-5 font-semibold">${s.name}</td>
            <td class="py-3.5 px-5 text-xs text-slate-500">${s.parent} (${s.phone})</td>
            <td class="py-3.5 px-5 text-right">
              <div class="inline-flex rounded-xl bg-slate-100 dark:bg-slate-800 p-1 gap-1">
                ${statuses.map(st => {
                  const active = current === st;
                  let activeClass = '';
                  if (active && st === 'Present') activeClass = 'bg-emerald-600 text-white shadow-sm';
                  if (active && st === 'Absent') activeClass = 'bg-rose-600 text-white shadow-sm';
                  if (active && st === 'Late') activeClass = 'bg-amber-500 text-white shadow-sm';
                  if (active && st === 'Excused') activeClass = 'bg-blue-600 text-white shadow-sm';

                  return `
                    <button onclick="setStudentAttendance('${s.id}', '${st}')"
                      class="px-2.5 py-1 rounded-lg text-xs font-semibold transition ${
                        active ? activeClass : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                      }">
                      ${st}
                    </button>
                  `;
                }).join('')}
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    function setStudentAttendance(studentId, status) {
      state.attendanceRecords[studentId] = status;
      renderMainView();
    }

    function markAllPresent() {
      state.students.forEach(s => { state.attendanceRecords[s.id] = 'Present'; });
      renderMainView();
      showToast('All students marked Present', 'info');
    }

    function saveAttendanceAndNotify() {
      const absentees = state.students.filter(s => state.attendanceRecords[s.id] === 'Absent');
      if (absentees.length > 0) {
        showToast(`Attendance Saved! Automated SMS alerts sent to ${absentees.length} parent(s) regarding absence.`, 'success');
      } else {
        showToast('Attendance Saved! 100% present/accounted for.', 'success');
      }
    }

    // --- E. EXAMINATION & GRADING SYSTEM ---
    function calculateGradeInfo(avg) {
      if (avg >= 90) return { grade: 'A+', gpa: '4.00', badge: 'bg-emerald-500/10 text-emerald-600' };
      if (avg >= 80) return { grade: 'A', gpa: '3.70', badge: 'bg-brand-500/10 text-brand-600' };
      if (avg >= 70) return { grade: 'B', gpa: '3.00', badge: 'bg-blue-500/10 text-blue-600' };
      if (avg >= 60) return { grade: 'C', gpa: '2.30', badge: 'bg-amber-500/10 text-amber-600' };
      return { grade: 'F', gpa: '0.00', badge: 'bg-rose-500/10 text-rose-600' };
    }

    function renderExamsView() {
      // Calculate totals & sort by rank dynamically
      const ranked = state.examMarks.map(m => {
        const total = m.math + m.physics + m.chemistry + m.biology + m.english + m.islamic;
        const avg = (total / 6).toFixed(1);
        return { ...m, total, avg: parseFloat(avg) };
      }).sort((a, b) => b.total - a.total);

      return `
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div>
              <h3 class="font-bold text-base">Mid-Term Marks Entry & Auto-Grading Grid (Grade 11-A)</h3>
              <p class="text-xs text-slate-500">Edit any subject mark (0–100) to automatically recalculate Total, GPA, Grade & Class Rank</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs px-3 py-1.5 rounded-xl bg-brand-50 dark:bg-brand-950/50 text-brand-700 dark:text-brand-300 font-semibold border border-brand-200 dark:border-brand-800">
                <i class="fa-solid fa-calculator mr-1"></i> Live GPA Engine Active
              </span>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 text-[11px] uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                  <th class="py-3.5 px-4 font-semibold">Rank</th>
                  <th class="py-3.5 px-4 font-semibold">Student</th>
                  <th class="py-3.5 px-3 font-semibold text-center">Math</th>
                  <th class="py-3.5 px-3 font-semibold text-center">Physics</th>
                  <th class="py-3.5 px-3 font-semibold text-center">Chem</th>
                  <th class="py-3.5 px-3 font-semibold text-center">Bio</th>
                  <th class="py-3.5 px-3 font-semibold text-center">English</th>
                  <th class="py-3.5 px-3 font-semibold text-center">Islamic</th>
                  <th class="py-3.5 px-4 font-semibold text-center">Total / 600</th>
                  <th class="py-3.5 px-4 font-semibold text-center">Avg & Grade</th>
                  <th class="py-3.5 px-4 font-semibold text-right">Transcript</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                ${ranked.map((row, idx) => {
                  const info = calculateGradeInfo(row.avg);
                  return `
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                      <td class="py-3 px-4 font-extrabold text-slate-700 dark:text-slate-200">#${idx + 1}</td>
                      <td class="py-3 px-4">
                        <div class="font-semibold">${row.name}</div>
                        <div class="text-[11px] font-mono text-slate-400">${row.studentId}</div>
                      </td>
                      ${['math', 'physics', 'chemistry', 'biology', 'english', 'islamic'].map(sub => `
                        <td class="py-3 px-2 text-center">
                          <input type="number" min="0" max="100" value="${row[sub]}"
                            onchange="updateExamMark('${row.studentId}', '${sub}', this.value)"
                            class="w-14 text-center py-1 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-semibold text-xs focus:outline-none focus:border-brand-500">
                        </td>
                      `).join('')}
                      <td class="py-3 px-4 text-center font-bold">${row.total}</td>
                      <td class="py-3 px-4 text-center">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold ${info.badge}">${row.avg}% (${info.grade})</span>
                      </td>
                      <td class="py-3 px-4 text-right">
                        <button onclick="openReportCardModal('${row.studentId}')" class="px-3 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition shadow-sm">
                          <i class="fa-solid fa-file-certificate mr-1"></i> Report Card
                        </button>
                      </td>
                    </tr>
                  `;
                }).join('')}
              </tbody>
            </table>
          </div>
        </div>
      `;
    }

    function updateExamMark(studentId, subject, val) {
      const numeric = Math.max(0, Math.min(100, parseInt(val) || 0));
      const record = state.examMarks.find(m => m.studentId === studentId);
      if (record) {
        record[subject] = numeric;
        renderMainView();
        showToast('Marks updated & class ranking recalculated', 'info');
      }
    }

    // --- F. FINANCE & FEE MANAGEMENT ---
    function renderFinanceView() {
      const totalBilled = state.invoices.reduce((a, b) => a + b.amount, 0);
      const totalPaid = state.invoices.reduce((a, b) => a + b.paid, 0);
      const totalDue = totalBilled - totalPaid;

      return `
        <!-- Finance Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
          <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
            <p class="text-xs font-semibold uppercase text-slate-500">Total Invoiced (Sample Cohort)</p>
            <h3 class="text-2xl font-extrabold mt-1">$${totalBilled.toLocaleString()}</h3>
          </div>
          <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
            <p class="text-xs font-semibold uppercase text-slate-500">Collected via Zaad / EVC / Bank</p>
            <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">$${totalPaid.toLocaleString()}</h3>
          </div>
          <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
            <p class="text-xs font-semibold uppercase text-slate-500">Outstanding Defaulters Balance</p>
            <h3 class="text-2xl font-extrabold text-rose-600 mt-1">$${totalDue.toLocaleString()}</h3>
          </div>
        </div>

        <!-- Invoices & Fee Collection Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div>
              <h3 class="font-bold text-base">Student Fee Invoices & Mobile Money Ledger</h3>
              <p class="text-xs text-slate-500">Supports Zaad, EVC Plus, Sahal, Cash, and Bank Transfer with instant receipts</p>
            </div>
            <button onclick="exportToCSV('finance')" class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
              <i class="fa-solid fa-file-excel mr-1.5 text-emerald-600"></i> Export Financial Report
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 text-[11px] uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                  <th class="py-3.5 px-5 font-semibold">Invoice #</th>
                  <th class="py-3.5 px-5 font-semibold">Student</th>
                  <th class="py-3.5 px-5 font-semibold">Fee Description</th>
                  <th class="py-3.5 px-5 font-semibold">Amount</th>
                  <th class="py-3.5 px-5 font-semibold">Paid</th>
                  <th class="py-3.5 px-5 font-semibold">Balance</th>
                  <th class="py-3.5 px-5 font-semibold">Method</th>
                  <th class="py-3.5 px-5 font-semibold">Status</th>
                  <th class="py-3.5 px-5 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                ${state.invoices.map(inv => {
                  const balance = inv.amount - inv.paid;
                  let badge = 'bg-emerald-500/10 text-emerald-600';
                  if (inv.status === 'Partial') badge = 'bg-amber-500/10 text-amber-600';
                  if (inv.status === 'Overdue') badge = 'bg-rose-500/10 text-rose-600';

                  return `
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                      <td class="py-3.5 px-5 font-mono text-xs font-semibold">${inv.id}</td>
                      <td class="py-3.5 px-5">
                        <div class="font-semibold">${inv.studentName}</div>
                        <div class="text-[11px] text-slate-400">${inv.studentId} • ${inv.class}</div>
                      </td>
                      <td class="py-3.5 px-5 text-xs">${inv.feeType}</td>
                      <td class="py-3.5 px-5 font-semibold">$${inv.amount}</td>
                      <td class="py-3.5 px-5 font-semibold text-emerald-600">$${inv.paid}</td>
                      <td class="py-3.5 px-5 font-bold ${balance > 0 ? 'text-rose-600' : 'text-slate-400'}">$${balance}</td>
                      <td class="py-3.5 px-5 text-xs font-medium">${inv.method}</td>
                      <td class="py-3.5 px-5">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold ${badge}">${inv.status}</span>
                      </td>
                      <td class="py-3.5 px-5 text-right space-x-1">
                        ${balance > 0 ? `
                          <button onclick="openRecordPaymentModal('${inv.id}')" class="px-2.5 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition">
                            Pay Now
                          </button>
                        ` : ''}
                        <button onclick="openReceiptModal('${inv.id}')" class="px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-semibold transition">
                          <i class="fa-solid fa-print"></i> Receipt
                        </button>
                      </td>
                    </tr>
                  `;
                }).join('')}
              </tbody>
            </table>
          </div>
        </div>
      `;
    }

    // --- G. HR, NOTICE BOARD & LIBRARY ---
    function renderHrLibraryView() {
      return `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Library Catalog -->
          <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
              <div>
                <h3 class="font-bold text-base">Amis School Library Inventory</h3>
                <p class="text-xs text-slate-500">ISBN tracking & book circulation</p>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-brand-500/10 text-brand-600 text-xs font-bold">1,840 Total Books</span>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
              ${state.libraryBooks.map(b => `
                <div class="p-4 flex items-center justify-between gap-4">
                  <div>
                    <p class="font-semibold text-sm">${b.title}</p>
                    <p class="text-xs text-slate-500">${b.author} • ISBN: <span class="font-mono">${b.isbn}</span></p>
                  </div>
                  <div class="text-right shrink-0">
                    <span class="text-xs font-bold text-emerald-600 block">${b.available} / ${b.copies} Available</span>
                    <button onclick="showToast('Book circulation logged in Library Ledger', 'success')" class="mt-1 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-brand-600 hover:text-white text-[11px] font-semibold transition">
                      Issue Book
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>

          <!-- Post New Announcement & Notice Board -->
          <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <h3 class="font-bold text-base mb-3">Publish School Announcement</h3>
              <form onsubmit="publishNotice(event)" class="space-y-3">
                <input id="notice-title" type="text" required placeholder="Announcement Headline..." class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm">
                <textarea id="notice-desc" rows="2" required placeholder="Message details for students, parents, or staff..." class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm"></textarea>
                <div class="flex justify-end">
                  <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Broadcast Notice
                  </button>
                </div>
              </form>
            </div>
            ${renderNoticeWidget()}
          </div>
        </div>
      `;
    }

    function publishNotice(e) {
      e.preventDefault();
      const title = document.getElementById('notice-title').value.trim();
      const desc = document.getElementById('notice-desc').value.trim();
      if (!title || !desc) return;
      state.notices.unshift({
        id: Date.now(),
        title,
        audience: 'All Users',
        date: 'Just Now',
        priority: 'High',
        desc
      });
      renderMainView();
      showToast('Announcement broadcasted to all role dashboards!', 'success');
    }

    // --- H. SYSTEM ARCHITECTURE & SQL DATABASE BLUEPRINT ---
    function renderBlueprintView() {
      const sqlSchema = `-- ============================================================================
-- AMIS SCHOOL MANAGEMENT SYSTEM (ASMS) - RELATIONAL DATABASE SCHEMA (PostgreSQL)
-- ============================================================================

CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL -- 'SUPER_ADMIN', 'ACCOUNTANT', 'TEACHER', 'STUDENT', 'PARENT', 'LIBRARIAN'
);

CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    role_id INT REFERENCES roles(id),
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    phone VARCHAR(30),
    password_hash TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE academic_years (
    id SERIAL PRIMARY KEY,
    name VARCHAR(20) UNIQUE NOT NULL, -- e.g., '2026-2027'
    is_current BOOLEAN DEFAULT FALSE
);

CREATE TABLE classes (
    id SERIAL PRIMARY KEY,
    grade_name VARCHAR(30) NOT NULL, -- 'Grade 1' .. 'Grade 12'
    section VARCHAR(10) NOT NULL,    -- 'A', 'B', 'C'
    class_teacher_id UUID REFERENCES users(id),
    room_number VARCHAR(30)
);

CREATE TABLE students (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    admission_no VARCHAR(25) UNIQUE NOT NULL, -- e.g., 'AMIS-2026-001'
    user_id UUID REFERENCES users(id),
    guardian_id UUID REFERENCES users(id),
    class_id INT REFERENCES classes(id),
    gender VARCHAR(10) NOT NULL,
    date_of_birth DATE NOT NULL,
    blood_group VARCHAR(5),
    medical_notes TEXT,
    status VARCHAR(20) DEFAULT 'Active'
);

CREATE TABLE subjects (
    id SERIAL PRIMARY KEY,
    code VARCHAR(15) UNIQUE NOT NULL,
    name VARCHAR(80) NOT NULL
);

CREATE TABLE attendance (
    id BIGSERIAL PRIMARY KEY,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    class_id INT REFERENCES classes(id),
    attendance_date DATE NOT NULL,
    status VARCHAR(15) CHECK (status IN ('Present', 'Absent', 'Late', 'Excused')),
    recorded_by UUID REFERENCES users(id),
    UNIQUE (student_id, attendance_date)
);

CREATE TABLE exams (
    id SERIAL PRIMARY KEY,
    academic_year_id INT REFERENCES academic_years(id),
    name VARCHAR(80) NOT NULL, -- 'Term 1 Mid-Term', 'Term 1 Final'
    start_date DATE
);

CREATE TABLE exam_marks (
    id BIGSERIAL PRIMARY KEY,
    exam_id INT REFERENCES exams(id),
    student_id UUID REFERENCES students(id),
    subject_id INT REFERENCES subjects(id),
    marks_obtained NUMERIC(5,2) CHECK (marks_obtained BETWEEN 0 AND 100),
    grade_letter VARCHAR(5),
    updated_by UUID REFERENCES users(id),
    UNIQUE (exam_id, student_id, subject_id)
);

CREATE TABLE fee_invoices (
    id SERIAL PRIMARY KEY,
    invoice_no VARCHAR(30) UNIQUE NOT NULL,
    student_id UUID REFERENCES students(id),
    academic_year_id INT REFERENCES academic_years(id),
    fee_category VARCHAR(60) NOT NULL,
    total_amount NUMERIC(10,2) NOT NULL,
    paid_amount NUMERIC(10,2) DEFAULT 0.00,
    due_date DATE NOT NULL,
    status VARCHAR(20) CHECK (status IN ('Paid', 'Partial', 'Overdue'))
);

CREATE TABLE fee_transactions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    invoice_id INT REFERENCES fee_invoices(id),
    amount_paid NUMERIC(10,2) NOT NULL,
    payment_method VARCHAR(30), -- 'Zaad', 'EVC Plus', 'Sahal', 'Cash', 'Bank Transfer'
    reference_code VARCHAR(80),
    received_by UUID REFERENCES users(id),
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE audit_logs (
    id BIGSERIAL PRIMARY KEY,
    actor_user_id UUID REFERENCES users(id),
    action VARCHAR(100) NOT NULL,
    entity_table VARCHAR(50),
    entity_id VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);`;

      return `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="space-y-5">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <h3 class="font-bold text-base text-brand-600 mb-3">Recommended Production Stack</h3>
              <ul class="space-y-3 text-xs text-slate-600 dark:text-slate-300">
                <li class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                  <strong class="block text-slate-900 dark:text-white">Frontend Web & Mobile PWA</strong>
                  Next.js / React + Tailwind CSS + Chart.js + Offline Service Workers
                </li>
                <li class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                  <strong class="block text-slate-900 dark:text-white">Backend API & RBAC Security</strong>
                  Node.js (NestJS/Express) or Laravel with JWT, Argon2 Hashing, Rate Limiting & Audit Logs
                </li>
                <li class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                  <strong class="block text-slate-900 dark:text-white">Database & Storage</strong>
                  PostgreSQL 16 (Relational Integrity) + S3/MinIO for Student Photos & Transcript PDFs
                </li>
                <li class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                  <strong class="block text-slate-900 dark:text-white">Somali Payment & SMS Gateways</strong>
                  WaafiPay / Telesom Zaad / Golis Sahal APIs + Bulk SMS Gateway for Absentee Alerts
                </li>
              </ul>
            </div>
          </div>

          <div class="lg:col-span-2 bg-slate-900 text-slate-100 rounded-2xl border border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-3.5 bg-slate-950 border-b border-slate-800 flex items-center justify-between">
              <span class="font-mono text-xs font-semibold text-brand-400">amis_school_schema.sql (PostgreSQL / MySQL ERD)</span>
              <button onclick="copySqlSchema()" class="px-3 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-white transition">
                <i class="fa-regular fa-copy mr-1"></i> Copy SQL
              </button>
            </div>
            <pre id="sql-code-block" class="p-5 text-xs font-mono overflow-x-auto leading-relaxed text-emerald-300 max-h-[540px]">${sqlSchema}</pre>
          </div>
        </div>
      `;
    }

    function copySqlSchema() {
      const text = document.getElementById('sql-code-block').innerText;
      const textarea = document.createElement('textarea');
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      showToast('SQL Database Schema copied to clipboard!', 'success');
    }

    // =========================================================================
    // 5. INTERACTIVE MODALS (ADMIT STUDENT, PAY FEE, REPORT CARD, RECEIPT, ID)
    // =========================================================================
    function openModal(htmlContent) {
      const overlay = document.getElementById('modal-overlay');
      const box = document.getElementById('modal-box');
      box.innerHTML = htmlContent;
      overlay.classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('modal-overlay').classList.add('hidden');
    }

    // 1. Admit New Student Modal
    function openAdmitStudentModal() {
      const nextNum = String(state.students.length + 1).padStart(3, '0');
      const autoId = `AMIS-2026-${nextNum}`;

      openModal(`
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
          <div>
            <h3 class="font-bold text-lg">Digital Student Admission Form</h3>
            <p class="text-xs text-slate-500">Auto-assigned Student ID: <span class="font-mono font-bold text-brand-600">${autoId}</span></p>
          </div>
          <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form onsubmit="submitNewStudent(event, '${autoId}')" class="p-6 space-y-4 text-sm">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold mb-1">Student Full Name *</label>
              <input id="new-stu-name" type="text" required placeholder="e.g., Maryan Cabdullaahi Xasan" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">Gender *</label>
              <select id="new-stu-gender" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                <option value="Female">Female</option>
                <option value="Male">Male</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">Assign Class *</label>
              <select id="new-stu-class" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                <option value="Grade 9">Grade 9</option>
                <option value="Grade 10">Grade 10</option>
                <option value="Grade 11" selected>Grade 11</option>
                <option value="Grade 12">Grade 12</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">Section *</label>
              <select id="new-stu-section" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                <option value="A">Section A</option>
                <option value="B">Section B</option>
                <option value="C">Section C</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">Parent / Guardian Name *</label>
              <input id="new-stu-parent" type="text" required placeholder="e.g., Cabdullaahi Xasan" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">Guardian Mobile (SMS Alerts) *</label>
              <input id="new-stu-phone" type="text" required placeholder="+252 63 4XXXXXX" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            </div>
          </div>
          <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold">Cancel</button>
            <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">Complete Admission</button>
          </div>
        </form>
      `);
    }

    function submitNewStudent(e, autoId) {
      e.preventDefault();
      const newStudent = {
        id: autoId,
        name: document.getElementById('new-stu-name').value.trim(),
        gender: document.getElementById('new-stu-gender').value,
        class: document.getElementById('new-stu-class').value,
        section: document.getElementById('new-stu-section').value,
        parent: document.getElementById('new-stu-parent').value.trim(),
        phone: document.getElementById('new-stu-phone').value.trim(),
        status: 'Active',
        dob: '2010-05-15',
        blood: 'O+',
        attendanceRate: 100
      };
      state.students.unshift(newStudent);
      state.attendanceRecords[autoId] = 'Present';
      closeModal();
      state.currentTab = 'students';
      renderSidebar();
      renderMainView();
      showToast(`Admitted ${newStudent.name} (${autoId}) successfully!`, 'success');
    }

    // 2. Record Fee Payment Modal
    function openRecordPaymentModal(invoiceId) {
      const inv = state.invoices.find(i => i.id === invoiceId);
      if (!inv) return;
      const balance = inv.amount - inv.paid;

      openModal(`
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
          <div>
            <h3 class="font-bold text-lg">Record Fee Payment</h3>
            <p class="text-xs text-slate-500">Invoice ${inv.id} • ${inv.studentName}</p>
          </div>
          <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form onsubmit="processFeePayment(event, '${inv.id}')" class="p-6 space-y-4 text-sm">
          <div class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/20 flex justify-between items-center">
            <span class="text-xs font-semibold text-amber-800 dark:text-amber-300">Remaining Balance Due:</span>
            <span class="text-lg font-extrabold text-amber-700 dark:text-amber-400">$${balance}</span>
          </div>
          <div>
            <label class="block text-xs font-semibold mb-1">Amount Paying Now ($ USD) *</label>
            <input id="pay-amount" type="number" min="1" max="${balance}" value="${balance}" required class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold">
          </div>
          <div>
            <label class="block text-xs font-semibold mb-1">Payment Channel *</label>
            <select id="pay-method" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
              <option value="Zaad">Zaad Service (Telesom)</option>
              <option value="EVC Plus">EVC Plus (Hormuud)</option>
              <option value="Sahal">Sahal Service (Golis)</option>
              <option value="Bank Transfer">Dahabshiil / Premier Bank Transfer</option>
              <option value="Cash">Cash at Bursar Desk</option>
            </select>
          </div>
          <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold">Cancel</button>
            <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">Confirm Payment & Print Receipt</button>
          </div>
        </form>
      `);
    }

    function processFeePayment(e, invoiceId) {
      e.preventDefault();
      const inv = state.invoices.find(i => i.id === invoiceId);
      const amount = parseFloat(document.getElementById('pay-amount').value) || 0;
      const method = document.getElementById('pay-method').value;

      inv.paid += amount;
      inv.method = method;
      inv.date = '2026-11-23';
      inv.status = inv.paid >= inv.amount ? 'Paid' : 'Partial';

      renderMainView();
      showToast(`Payment of $${amount} recorded via ${method}!`, 'success');
      openReceiptModal(invoiceId);
    }

    // 3. Official Printable Fee Receipt Modal
    function openReceiptModal(invoiceId) {
      const inv = state.invoices.find(i => i.id === invoiceId);
      if (!inv) return;
      const balance = inv.amount - inv.paid;

      openModal(`
        <div id="printable-area" class="p-8 bg-white text-slate-900">
          <div class="flex items-center justify-between border-b-2 border-brand-600 pb-4 mb-6">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-graduation-cap"></i>
              </div>
              <div>
                <h2 class="text-xl font-extrabold tracking-tight text-slate-900">AMIS SCHOOL</h2>
                <p class="text-xs text-slate-500">Official Financial Payment Receipt • AY ${state.academicYear}</p>
              </div>
            </div>
            <div class="text-right">
              <span class="px-3 py-1 rounded-full text-xs font-bold ${inv.status === 'Paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}">${inv.status.toUpperCase()}</span>
              <p class="font-mono text-xs font-bold mt-1">${inv.id}</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 text-xs mb-6 bg-slate-50 p-4 rounded-xl">
            <div>
              <p class="text-slate-400">Received From (Student):</p>
              <p class="font-bold text-sm text-slate-900">${inv.studentName}</p>
              <p class="text-slate-600">${inv.studentId} • ${inv.class}</p>
            </div>
            <div class="text-right">
              <p class="text-slate-400">Payment Method & Date:</p>
              <p class="font-bold text-sm text-slate-900">${inv.method}</p>
              <p class="text-slate-600">Date: ${inv.date}</p>
            </div>
          </div>

          <table class="w-full text-left text-xs border-collapse mb-6">
            <thead>
              <tr class="border-b border-slate-300 text-slate-500 uppercase">
                <th class="py-2">Description</th>
                <th class="py-2 text-right">Billed</th>
                <th class="py-2 text-right">Paid</th>
                <th class="py-2 text-right">Balance</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-slate-200 font-semibold">
                <td class="py-3">${inv.feeType}</td>
                <td class="py-3 text-right">$${inv.amount.toFixed(2)}</td>
                <td class="py-3 text-right text-emerald-700">$${inv.paid.toFixed(2)}</td>
                <td class="py-3 text-right text-rose-600">$${balance.toFixed(2)}</td>
              </tr>
            </tbody>
          </table>

          <div class="flex items-center justify-between text-[11px] text-slate-500 pt-4 border-t border-slate-200">
            <div>
              <p>Authorized Finance Officer: <strong>Sahra Maxamuud Xasan</strong></p>
              <p>Generated digitally by Amis School Management System (ASMS)</p>
            </div>
            <div class="w-12 h-12 border border-slate-300 rounded flex items-center justify-center text-slate-400">
              <i class="fa-solid fa-qrcode text-2xl text-slate-800"></i>
            </div>
          </div>
        </div>
        <div class="px-6 py-4 bg-slate-100 dark:bg-slate-800 flex justify-end gap-2 no-print">
          <button onclick="closeModal()" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-semibold">Close</button>
          <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold"><i class="fa-solid fa-print mr-1.5"></i> Print Official Receipt</button>
        </div>
      `);
    }

    // 4. Official Printable Report Card Modal
    function openReportCardModal(studentId) {
      const stu = state.students.find(s => s.id === studentId) || state.students[0];
      const marks = state.examMarks.find(m => m.studentId === studentId) || state.examMarks[0];
      const subjects = [
        { name: 'Advanced Mathematics', score: marks.math },
        { name: 'Physics', score: marks.physics },
        { name: 'Chemistry', score: marks.chemistry },
        { name: 'Biology', score: marks.biology },
        { name: 'English Language', score: marks.english },
        { name: 'Islamic Studies', score: marks.islamic },
      ];
      const total = subjects.reduce((s, item) => s + item.score, 0);
      const avg = (total / subjects.length).toFixed(1);
      const overall = calculateGradeInfo(parseFloat(avg));

      openModal(`
        <div id="printable-area" class="p-8 bg-white text-slate-900">
          <!-- Header -->
          <div class="flex items-center justify-between border-b-2 border-brand-700 pb-4 mb-5">
            <div class="flex items-center gap-3">
              <div class="w-14 h-14 rounded-2xl bg-brand-700 text-white flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-graduation-cap"></i>
              </div>
              <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">AMIS SECONDARY SCHOOL</h2>
                <p class="text-xs font-semibold text-brand-700">Official Academic Transcript & Term 1 Report Card</p>
                <p class="text-[11px] text-slate-500">Academic Year ${state.academicYear} • Excellence in Education & Character</p>
              </div>
            </div>
            <div class="text-right">
              <i class="fa-solid fa-qrcode text-4xl text-slate-800"></i>
              <p class="text-[9px] font-mono text-slate-400 mt-0.5">VERIFIED-${stu.id}</p>
            </div>
          </div>

          <!-- Student Info Grid -->
          <div class="grid grid-cols-3 gap-3 text-xs bg-slate-50 p-4 rounded-xl border border-slate-200 mb-5">
            <div><span class="text-slate-400 block">Student Name:</span><strong>${stu.name}</strong></div>
            <div><span class="text-slate-400 block">Admission ID:</span><strong class="font-mono">${stu.id}</strong></div>
            <div><span class="text-slate-400 block">Class & Section:</span><strong>${stu.class} - Sec ${stu.section}</strong></div>
            <div><span class="text-slate-400 block">Guardian:</span><strong>${stu.parent}</strong></div>
            <div><span class="text-slate-400 block">Attendance:</span><strong>${stu.attendanceRate}%</strong></div>
            <div><span class="text-slate-400 block">Overall GPA / Grade:</span><strong class="text-brand-700">${overall.gpa} (${overall.grade})</strong></div>
          </div>

          <!-- Marks Breakdown -->
          <table class="w-full text-left text-xs border-collapse border border-slate-300 mb-5">
            <thead>
              <tr class="bg-brand-700 text-white">
                <th class="py-2.5 px-3 border border-brand-700">Subject</th>
                <th class="py-2.5 px-3 border border-brand-700 text-center">Max Marks</th>
                <th class="py-2.5 px-3 border border-brand-700 text-center">Marks Obtained</th>
                <th class="py-2.5 px-3 border border-brand-700 text-center">Grade</th>
                <th class="py-2.5 px-3 border border-brand-700">Teacher Remarks</th>
              </tr>
            </thead>
            <tbody>
              ${subjects.map(sub => {
                const g = calculateGradeInfo(sub.score);
                return `
                  <tr>
                    <td class="py-2 px-3 border border-slate-300 font-semibold">${sub.name}</td>
                    <td class="py-2 px-3 border border-slate-300 text-center">100</td>
                    <td class="py-2 px-3 border border-slate-300 text-center font-bold">${sub.score}</td>
                    <td class="py-2 px-3 border border-slate-300 text-center font-bold">${g.grade}</td>
                    <td class="py-2 px-3 border border-slate-300 text-slate-600">${sub.score >= 85 ? 'Outstanding mastery' : 'Good consistent effort'}</td>
                  </tr>
                `;
              }).join('')}
              <tr class="bg-slate-100 font-extrabold">
                <td class="py-2.5 px-3 border border-slate-300">AGGREGATE TOTAL & AVERAGE</td>
                <td class="py-2.5 px-3 border border-slate-300 text-center">600</td>
                <td class="py-2.5 px-3 border border-slate-300 text-center text-brand-700">${total} (${avg}%)</td>
                <td class="py-2.5 px-3 border border-slate-300 text-center text-brand-700">${overall.grade}</td>
                <td class="py-2.5 px-3 border border-slate-300">Promoted / Good Standing</td>
              </tr>
            </tbody>
          </table>

          <!-- Signatures -->
          <div class="grid grid-cols-2 gap-8 pt-6 text-xs">
            <div>
              <div class="border-b border-slate-400 w-48 mb-1"></div>
              <p class="font-bold">Ustaad Cali Warsame</p>
              <p class="text-slate-500">Class Teacher Signature</p>
            </div>
            <div class="text-right">
              <div class="border-b border-slate-400 w-48 ml-auto mb-1"></div>
              <p class="font-bold">Dr. Cabdiraxmaan Nuur</p>
              <p class="text-slate-500">Principal, Amis School</p>
            </div>
          </div>
        </div>
        <div class="px-6 py-4 bg-slate-100 dark:bg-slate-800 flex justify-end gap-2 no-print">
          <button onclick="closeModal()" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-semibold">Close</button>
          <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold"><i class="fa-solid fa-print mr-1.5"></i> Print Report Card</button>
        </div>
      `);
    }

    // 5. Student ID Card Modal
    function openStudentIdModal(studentId) {
      const stu = state.students.find(s => s.id === studentId);
      if (!stu) return;

      openModal(`
        <div id="printable-area" class="p-8 bg-white flex justify-center">
          <div class="w-80 rounded-2xl overflow-hidden border-2 border-brand-700 shadow-lg bg-white text-slate-900">
            <div class="bg-gradient-to-r from-brand-700 to-teal-800 p-4 text-white text-center">
              <div class="w-10 h-10 rounded-xl bg-white/20 mx-auto flex items-center justify-center text-lg font-bold mb-1">
                <i class="fa-solid fa-graduation-cap"></i>
              </div>
              <h3 class="font-extrabold text-sm tracking-wider">AMIS SCHOOL</h3>
              <p class="text-[10px] text-brand-100 uppercase">Official Student Identity Card</p>
            </div>
            <div class="p-5 text-center">
              <div class="w-20 h-20 rounded-2xl bg-slate-100 border-2 border-brand-500 mx-auto flex items-center justify-center text-2xl font-extrabold text-brand-700 mb-3">
                ${stu.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
              </div>
              <h4 class="font-bold text-base text-slate-900">${stu.name}</h4>
              <p class="font-mono text-xs font-bold text-brand-700 mt-0.5">${stu.id}</p>

              <div class="mt-4 text-left text-xs space-y-1.5 bg-slate-50 p-3 rounded-xl border border-slate-200">
                <div class="flex justify-between"><span class="text-slate-400">Class:</span><strong>${stu.class} - Sec ${stu.section}</strong></div>
                <div class="flex justify-between"><span class="text-slate-400">Blood Group:</span><strong class="text-rose-600">${stu.blood}</strong></div>
                <div class="flex justify-between"><span class="text-slate-400">Emergency:</span><strong>${stu.phone}</strong></div>
                <div class="flex justify-between"><span class="text-slate-400">Valid Thru:</span><strong>July 2027</strong></div>
              </div>
            </div>
          </div>
        </div>
        <div class="px-6 py-4 bg-slate-100 dark:bg-slate-800 flex justify-end gap-2 no-print">
          <button onclick="closeModal()" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-semibold">Close</button>
          <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold"><i class="fa-solid fa-print mr-1.5"></i> Print ID Card</button>
        </div>
      `);
    }

    // =========================================================================
    // 6. UTILITIES: CSV EXPORT & TOAST NOTIFICATIONS
    // =========================================================================
    function exportToCSV(type) {
      let csvContent = '';
      let filename = '';

      if (type === 'students') {
        csvContent = 'Student ID,Full Name,Gender,Class,Section,Guardian,Phone,Status\n' +
          state.students.map(s => `"${s.id}","${s.name}","${s.gender}","${s.class}","${s.section}","${s.parent}","${s.phone}","${s.status}"`).join('\n');
        filename = `Amis_School_Students_${state.academicYear}.csv`;
      } else {
        csvContent = 'Invoice ID,Student ID,Student Name,Fee Type,Amount,Paid,Balance,Method,Status\n' +
          state.invoices.map(i => `"${i.id}","${i.studentId}","${i.studentName}","${i.feeType}",${i.amount},${i.paid},${i.amount - i.paid},"${i.method}","${i.status}"`).join('\n');
        filename = `Amis_School_Finance_${state.academicYear}.csv`;
      }

      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', filename);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      showToast(`Exported ${filename}`, 'success');
    }

    function showToast(message, type = 'info') {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      const colors = type === 'success'
        ? 'bg-slate-900 border-brand-500 text-white'
        : 'bg-slate-900 border-blue-500 text-white';
      const icon = type === 'success'
        ? '<i class="fa-solid fa-circle-check text-brand-400"></i>'
        : '<i class="fa-solid fa-circle-info text-blue-400"></i>';

      toast.className = `pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 shadow-xl text-xs font-medium transition-all transform translate-y-2 opacity-0 ${colors}`;
      toast.innerHTML = `${icon}<span>${message}</span>`;
      container.appendChild(toast);

      requestAnimationFrame(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
      });

      setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
      }, 3500);
    }
  </script>
</body>
</html>