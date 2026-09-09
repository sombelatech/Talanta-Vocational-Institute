const fs = require('fs');
const path = require('path');

module.exports = async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ success: false, error: 'Method not allowed.' });
  }

  const body = typeof req.body === 'string' ? JSON.parse(req.body) : (req.body || {});
  const name = String(body.name || '').trim();
  const email = String(body.email || '').trim();
  const phone = String(body.phone || '').trim();
  const program = String(body.program || '').trim();
  const qualification = String(body.qualification || '').trim();
  const dob = String(body.dob || '').trim();
  const address = String(body.address || '').trim();
  const message = String(body.message || '').trim();

  if (!name || !email || !phone || !program) {
    return res.status(400).json({
      success: false,
      error: 'Please complete all required fields.'
    });
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    return res.status(400).json({
      success: false,
      error: 'Please enter a valid email address.'
    });
  }

  const submission = {
    name,
    email,
    phone,
    program,
    qualification,
    dob,
    address,
    message,
    submitted_at: new Date().toISOString()
  };

  const fileDir = path.join('/', 'tmp');
  const filePath = path.join(fileDir, 'applications.json');
  let existing = [];

  try {
    fs.mkdirSync(fileDir, { recursive: true });
    if (fs.existsSync(filePath)) {
      const raw = fs.readFileSync(filePath, 'utf8');
      existing = raw ? JSON.parse(raw) : [];
      if (!Array.isArray(existing)) existing = [];
    }
  } catch (error) {
    existing = [];
  }

  existing.push(submission);
  fs.writeFileSync(filePath, JSON.stringify(existing, null, 2));

  return res.status(200).json({
    success: true,
    message: 'Application submitted successfully. Our admissions team will contact you soon.'
  });
};
