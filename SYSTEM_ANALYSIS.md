# Ngoconnect System Analysis

## Overview

Ngoconnect is a Laravel 10 platform for NGO discovery, volunteering, community engagement, and direct donation recording.

## Core Areas

- User management with admin, NGO, and people roles
- NGO profiles, verification, and discovery
- Event creation, volunteering, attendance, badges, and certificates
- Social feed, comments, follows, messaging, and community circles
- Recommendation logging and suggestion features
- Donation records that store donor, NGO, amount, and timestamps

## Donation Flow

- People users choose a verified NGO and submit an amount
- The application stores the donation directly in the `donations` table
- NGOs can review received donations in their dashboard

## Donation Schema

The `donations` table stores:

- `user_id`
- `ngo_id`
- `donation_amount`
- `created_at`
- `updated_at`
