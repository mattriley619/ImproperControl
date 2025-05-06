from db_config import db_config
import mysql.connector

try:
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    cursor.execute("""
        SELECT * FROM Equation
        WHERE solution IS NULL
        LIMIT 1
    """)
    row = cursor.fetchone()

    if row:
        equation = row['equation']

        for char in equation:
            if char.isalpha():
                print("Equation:", equation)
                print("Invalid equation. Exiting.")
                cursor.execute("""
                                UPDATE Equation
                                SET solution = %s
                                WHERE equation = %s
                            """, ('invalid', equation))
                conn.commit()
                exit(1)

        print(f"Solving: {equation}")
        try:
            solution = eval(equation)
        except Exception as eval_error:
            print(f"Error evaluating equation: {eval_error}")
            solution = None

        if solution is not None:
            cursor.execute("""
                UPDATE Equation
                SET solution = %s
                WHERE equation = %s
            """, (solution, equation))
            conn.commit()
            print(f"Stored solution: {solution}")
        else:
            print("No valid solution to store.")
    else:
        print("No unsolved equations found.")

    cursor.close()
    conn.close()

except Exception as e:
    print(f"Database error: {e}")
