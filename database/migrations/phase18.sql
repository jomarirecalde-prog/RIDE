-- Consolidated Ongoing Researches document category
ALTER TABLE documents
MODIFY category ENUM(
    'proposal',
    'report',
    'publication',
    'patent',
    'extension_media',
    'other',
    'completed_researches',
    'ongoing_researches',
    'research_output_published',
    'research_output_presented',
    'commercialized',
    'resulted_in_extension',
    'journal_citation',
    'book_citation',
    'inventions_um_copyrights',
    'linkages',
    'consolidated_completed_researches',
    'consolidated_ongoing_researches',
    'consolidated_research_output_published',
    'progress_report',
    'terminal_report',
    'terminal_report_assessment_form',
    'obr_matrix',
    'trainings_conducted'
) NOT NULL DEFAULT 'other';
