<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('otherName')->nullable();
            $table->string('email')->unique()->nullable();
            $table->enum('role', ['admin', 'headteacher', 'teacher', 'student', 'parent', 'bursar', 'dos', 'librarian'])->default('student');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();  // Only for roles that require password
            $table->string('profile_photo')->nullable(); // Store path to profile photos
            $table->boolean('is_active')->default(true); // Account status
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // For soft deleting users
        });

        // Student Table
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('dateOfBirth');
            $table->string('gender');
            $table->string('nationality');
            $table->string('religion');
            $table->string('admissionNumber')->unique();
            $table->string('guardianContact')->nullable(); // Contact for parents/guardians
            $table->text('address')->nullable(); // Physical address
            $table->text('medical_conditions')->nullable(); // Health information
            $table->date('admission_date'); // When the student joined the school
            $table->timestamps();
            $table->softDeletes();
        });

        // Parent Table
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('relationship')->nullable(); // Parent-Child relationship (e.g., mother, father)
            $table->string('phoneNumber');
            $table->string('alternativePhoneNumber')->nullable(); // Secondary contact
            $table->text('address')->nullable(); // Physical address
            $table->string('occupation')->nullable(); // Parent's job
            $table->timestamps();
            $table->softDeletes();
        });

        // Bursar Table
        Schema::create('bursars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('staffId')->unique(); // Staff identification number
            $table->enum('role', ['chief_bursar', 'assistant_bursar', 'accounts_clerk', 'cashier'])->default('cashier');
            $table->string('department')->nullable(); // Department details
            $table->string('phoneNumber');
            $table->decimal('transaction_limit', 12, 2)->nullable(); // Maximum transaction amount they can process
            $table->boolean('can_approve_expenses')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Teacher Table
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('staffId')->unique(); // Staff identification number
            $table->string('subject');
            $table->string('qualification');
            $table->string('phoneNumber');
            $table->date('employment_date')->nullable(); // When teacher was hired
            $table->timestamps();
            $table->softDeletes();
        });

        // Headteacher Table
        Schema::create('headteacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('staffId')->unique(); // Staff identification number
            $table->string('qualification');
            $table->string('phoneNumber');
            $table->timestamps();
            $table->softDeletes();
        });

        // Dean of Students (DOS) Table
        Schema::create('deans_of_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('staffId')->unique(); // Staff identification number
            $table->string('department')->nullable();
            $table->string('phoneNumber');
            $table->timestamps();
            $table->softDeletes();
        });

        // Admin Table
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('phoneNumber')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });



        // Password Reset Tokens Table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions Table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Academic Years Table
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // E.g., "2025 Academic Year"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        // Terms
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->string('name'); // Term 1, Term 2, Term 3
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        // Payments and Fees Table
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('terms');
            $table->decimal('total_amount', 10, 2); // Total fee amount
            $table->decimal('paid_amount', 10, 2)->default(0); // Amount paid so far
            $table->enum('status', ['paid', 'partial', 'pending', 'overdue'])->default('pending');
            $table->date('due_date'); // Due date for full payment
            $table->timestamps();
        });

        // Fee Structure Table
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_id')->constrained('terms');
            $table->string('class_level'); // S1, S2,
            $table->decimal('tuition', 10, 2);
            $table->decimal('boarding', 10, 2)->nullable();
            $table->decimal('development', 10, 2)->nullable();
            $table->decimal('uniform', 10, 2)->nullable();
            $table->decimal('books', 10, 2)->nullable();
            $table->decimal('other_charges', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Payments Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_id')->constrained('fees')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Amount paid in this transaction
            $table->string('payment_method'); // E.g., cash, bank transfer, mobile money
            $table->string('transaction_id')->nullable(); // For digital payments
            $table->string('receipt_number')->unique(); // School-generated receipt number
            $table->foreignId('received_by')->nullable()->constrained('users'); // Bursar who received payment
            $table->timestamp('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Subjects Table
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable(); // Nullable

            $table->text('description')->nullable();
            $table->enum('category', ['sciences', 'arts', 'languages', 'technical', 'others'])->nullable();
            $table->boolean('is_compulsory')->default(false);
            $table->timestamps();
        });

        // Classes Table
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Class name (e.g., Senior 1, Senior 2, etc.)
            $table->string('stream')->nullable(); // Stream/Section (e.g., A, B, C)
            $table->foreignId('class_teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->integer('capacity')->nullable(); // Maximum number of students
            $table->string('classroom_location')->nullable(); // Physical location
            $table->timestamps();
        });

        // Class Registrations Table
        Schema::create('class_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('terms');
            $table->enum('status', ['registered', 'pending'])->default('pending'); // If the student is registered or pending
            $table->date('registration_date');
            $table->timestamps();

            // Prevent duplicate registrations
            $table->unique(['student_id', 'class_id', 'term_id']);
        });

        // Student-Parent Relationship (Many-to-Many)
        Schema::create('parent_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('parent_id')->constrained('parents')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate relationships
            $table->unique(['student_id', 'parent_id']);
        });

        // Teacher-Subject Assignment
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('terms');
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['teacher_id', 'subject_id', 'class_id', 'term_id']);
        });

        // Timetable Table
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('term_id')->constrained('terms');
            $table->timestamps();
        });

        // Assessments Table
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // E.g., "Mid-term Test", "Final Exam"
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('terms');
            $table->date('assessment_date');
            $table->integer('max_score')->default(100);
            $table->enum('type', ['test', 'exam', 'assignment', 'project', 'practical']);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Student Marks Table
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->decimal('score', 5, 2); // Score obtained by the student
            $table->string('grade')->nullable(); // A, B, C, etc.
            $table->text('teacher_remarks')->nullable();
            $table->foreignId('recorded_by')->constrained('users'); // Teacher who recorded marks
            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['student_id', 'assessment_id']);
        });

        // Attendance Table
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->text('remarks')->nullable();
            $table->foreignId('marked_by')->constrained('users'); // Teacher who marked attendance
            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['student_id', 'class_id', 'date']);
        });

        // School Events Table
        Schema::create('school_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('organizer_id')->constrained('users');
            $table->timestamps();
        });

        // Notices/Announcements Table
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('posted_by')->constrained('users');
            $table->enum('audience', ['all', 'teachers', 'students', 'parents']);
            $table->date('publish_date');
            $table->date('expiry_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });



        // School Financial Accounts Table
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('account_number')->unique();
            $table->enum('account_type', ['bank', 'cash', 'mobile_money', 'investment', 'other']);
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Financial Transactions Table
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->string('reference_number')->unique();
            $table->string('transaction_category'); // E.g., "Fees", "Salary", "Utilities"
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->foreignId('recorded_by')->constrained('users'); // Bursar who recorded transaction
            $table->foreignId('approved_by')->nullable()->constrained('users'); // For expense approvals
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        // Budget Table
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('term_id')->constrained('terms');
            $table->decimal('total_amount', 12, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        // Budget Items Table
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('budgets')->onDelete('cascade');
            $table->string('name');
            $table->string('category'); // E.g., "Salaries", "Maintenance", "Supplies"
            $table->decimal('allocated_amount', 12, 2);
            $table->decimal('used_amount', 12, 2)->default(0);
            $table->timestamps();
        });

        // Expenses Table
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique(); // For tracking
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('budget_item_id')->nullable()->constrained('budget_items'); // Link to budget if applicable
            $table->decimal('amount', 12, 2);
            $table->string('payee'); // Who was paid
            $table->string('payment_method');
            $table->string('category'); // Type of expense
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->string('receipt_number')->nullable();
            $table->boolean('has_receipt')->default(false);
            $table->foreignId('recorded_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->timestamps();
        });

        // Salary Payments Table
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Staff member
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->date('payment_date');
            $table->string('payment_method');
            $table->string('transaction_id')->nullable();
            $table->string('month_year'); // E.g., "January 2025"
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->constrained('users'); // Bursar who processed
            $table->timestamps();
        });

        // Financial Reports Table (For tracking generated reports)
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->enum('report_type', ['income', 'expense', 'balance', 'summary', 'fees', 'salary', 'custom']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('file_path')->nullable(); // If reports are stored as files
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamps();
        });

        // Fee Arrears Table (For tracking historical fee balances)
        Schema::create('fee_arrears', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('term_id')->constrained('terms');
            $table->decimal('amount', 12, 2);
            $table->text('notes')->nullable();
            $table->boolean('is_cleared')->default(false);
            $table->date('cleared_date')->nullable();
            $table->timestamps();
        });

        // Fee Reminders Table (For tracking communications about fees)
        Schema::create('fee_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_id')->constrained('fees');
            $table->enum('medium', ['sms', 'email', 'letter', 'phone']);
            $table->text('message');
            $table->date('sent_date');
            $table->foreignId('sent_by')->constrained('users');
            $table->timestamps();
        });

        // Scholarships/Bursaries Table
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->string('scholarship_name');
            $table->string('sponsor')->nullable(); // Person or organization sponsoring
            $table->decimal('amount', 12, 2);
            $table->foreignId('term_id')->constrained('terms');
            $table->enum('type', ['full', 'partial', 'one-time']);
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in reverse order to respect foreign key constraints
        Schema::dropIfExists('scholarships');
        Schema::dropIfExists('fee_reminders');
        Schema::dropIfExists('fee_arrears');
        Schema::dropIfExists('financial_reports');
        Schema::dropIfExists('salary_payments');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('budget_items');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('school_events');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('marks');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('teacher_subjects');
        Schema::dropIfExists('parent_student');
        Schema::dropIfExists('class_registrations');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fees');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('deans_of_students');
        Schema::dropIfExists('headteachers');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('bursars');
        Schema::dropIfExists('parents');
        Schema::dropIfExists('students');
        Schema::dropIfExists('users');
    }
};