package edu.university.ride;

/**
 * Demo CLI: authenticates against the PHP API and prints proposals + stats.
 *
 * Usage:
 *   mvn -q package
 *   java -jar target/ride-api-client-1.0.0-SNAPSHOT.jar [baseUrl] [email] [password]
 */
public final class RideApiDemo {

    public static void main(String[] args) throws Exception {
        String baseUrl = args.length > 0 ? args[0] : "http://localhost/RIDE/public";
        String email = args.length > 1 ? args[1] : "director@ride.local";
        String password = args.length > 2 ? args[2] : "password123";

        RideApiClient client = new RideApiClient(baseUrl);
        System.out.println("Logging in as " + email + " ...");
        client.login(email, password);

        System.out.println("\n--- Proposals ---");
        System.out.println(client.getProposals());

        System.out.println("\n--- Stats ---");
        System.out.println(client.getStats());

        System.out.println("\n--- Ongoing Projects ---");
        System.out.println(client.getProjects());

        try {
            System.out.println("\n--- Extension Beneficiaries (3yr) ---");
            System.out.println(client.getExtensionBeneficiaries(3));
        } catch (IOException e) {
            System.out.println("(Skipped — requires reporter/director role)");
        }
    }
}
