const multer = require("multer");
const upload = multer({ dest: "uploads/" }); // Temporary storage

app.post("/send", upload.single("resume"), async (req, res) => {
    const {
        fullName, email, phone, location,
        jobRole, industry, experience, salary, notes
    } = req.body;

    const resumeFile = req.file; // Multer gives file info

    const htmlTemplate = `
        <html>
        <head>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        </head>
        <body style="font-family: 'Poppins', sans-serif; color: #333;">
            <h2 style="color: #004aad;">New Candidate Form Submission</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td><strong>Full Name:</strong></td><td>${fullName}</td></tr>
                <tr><td><strong>Email:</strong></td><td>${email}</td></tr>
                <tr><td><strong>Phone:</strong></td><td>${phone}</td></tr>
                <tr><td><strong>Location:</strong></td><td>${location}</td></tr>
                <tr><td><strong>Desired Job Role:</strong></td><td>${jobRole}</td></tr>
                <tr><td><strong>Industry:</strong></td><td>${industry}</td></tr>
                <tr><td><strong>Experience:</strong></td><td>${experience} years</td></tr>
                <tr><td><strong>Expected Salary:</strong></td><td>${salary || "Not specified"}</td></tr>
                <tr><td><strong>Notes:</strong></td><td>${notes || "None"}</td></tr>
            </table>
        </body>
        </html>
    `;

    const transporter = nodemailer.createTransport({
        service: "gmail",
        auth: {
            user: process.env.EMAIL_USER,
            pass: process.env.EMAIL_PASS
        }
    });

    try {
        const mailOptions = {
            from: email,
            to: process.env.EMAIL_TO,
            subject: `Candidate Form Submission: ${fullName}`,
            html: htmlTemplate,
            attachments: resumeFile ? [{
                filename: resumeFile.originalname,
                path: resumeFile.path
            }] : []
        };

        await transporter.sendMail(mailOptions);
        res.status(200).json({ message: "Email sent successfully!" });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Email sending failed." });
    }
});
