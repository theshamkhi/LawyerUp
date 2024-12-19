// Show Lawyer-specific fields based on role selection
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const lawyerFields = document.getElementById('lawyerFields');
    if (role === 'Lawyer') {
        lawyerFields.classList.remove('hidden');
    } else {
        lawyerFields.classList.add('hidden');
    }
});