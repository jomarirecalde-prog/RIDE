package edu.university.ride;

import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

import java.io.IOException;
import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.nio.charset.StandardCharsets;
import java.time.Duration;

/**
 * REST client for the PHP RIDE IMS API.
 */
public final class RideApiClient {

    private final String baseUrl;
    private final HttpClient http;
    private final Gson gson = new Gson();
    private String token;

    public RideApiClient(String baseUrl) {
        this.baseUrl = baseUrl.endsWith("/") ? baseUrl.substring(0, baseUrl.length() - 1) : baseUrl;
        this.http = HttpClient.newBuilder().connectTimeout(Duration.ofSeconds(10)).build();
    }

    public void login(String email, String password) throws IOException, InterruptedException {
        JsonObject body = new JsonObject();
        body.addProperty("email", email);
        body.addProperty("password", password);

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(baseUrl + "/api/login"))
                .header("Content-Type", "application/json")
                .POST(HttpRequest.BodyPublishers.ofString(gson.toJson(body), StandardCharsets.UTF_8))
                .build();

        HttpResponse<String> response = http.send(request, HttpResponse.BodyHandlers.ofString());
        if (response.statusCode() != 200) {
            throw new IOException("Login failed: HTTP " + response.statusCode() + " — " + response.body());
        }

        JsonObject json = JsonParser.parseString(response.body()).getAsJsonObject();
        this.token = json.get("token").getAsString();
    }

    public String getProposals() throws IOException, InterruptedException {
        return get("/api/proposals");
    }

    public String getStats() throws IOException, InterruptedException {
        return get("/api/stats");
    }

    public String getProjects() throws IOException, InterruptedException {
        return get("/api/projects");
    }

    public String getExtensionBeneficiaries(int years) throws IOException, InterruptedException {
        return get("/api/reports/extension-beneficiaries?years=" + years);
    }

    private String get(String path) throws IOException, InterruptedException {
        if (token == null || token.isBlank()) {
            throw new IllegalStateException("Call login() first.");
        }
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(baseUrl + path))
                .header("Authorization", "Bearer " + token)
                .GET()
                .build();

        HttpResponse<String> response = http.send(request, HttpResponse.BodyHandlers.ofString());
        if (response.statusCode() != 200) {
            throw new IOException("Request failed: HTTP " + response.statusCode() + " — " + response.body());
        }
        return response.body();
    }
}
